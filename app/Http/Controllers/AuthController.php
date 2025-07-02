<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario; // Asegúrate de que tu modelo Usuario esté importado
use Illuminate\Validation\ValidationException;
use App\Interfaces\UserServiceInterface; // Importa la interfaz de tu servicio de usuario
use Illuminate\Support\Facades\Password; // Facade para el broker de contraseñas
use Illuminate\Support\Facades\Session; // Para usar mensajes flash (aunque ya se usa with())
use Illuminate\Auth\Events\PasswordReset; // Para el evento de restablecimiento de contraseña
use Illuminate\Support\Facades\Event; // Para disparar eventos
use Illuminate\Support\Facades\Log; // Importa el Facade Log

class AuthController extends Controller
{
    private UserServiceInterface $userService;

    // Inyección de dependencia del servicio
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Muestra el formulario de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        Log::info('Accediendo al formulario de login.');
        return view('auth.login');
    }

    /**
     * Procesa la solicitud de login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // 1. Validar los datos de entrada
        $credentials = $request->validate([
            'username' => ['required', 'string'], // Puede ser username o email
            'password' => ['required', 'string'],
        ]);

        // Determinar si el usuario está intentando iniciar sesión con username o email
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Intentar autenticar al usuario
        if (Auth::attempt([$fieldType => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate(); // Regenerar la sesión para seguridad

            return redirect()->intended('/dashboard'); // Redirigir a una página de dashboard (o a donde el usuario intentaba ir)
        }

        // Si la autenticación falla, lanzar una excepción de validación
        throw ValidationException::withMessages([
            'username' => __('auth.failed'), // Mensaje de error de autenticación (puedes personalizarlo)
        ]);
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Muestra el formulario de registro.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Procesa la solicitud de registro de un nuevo usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        // Validar los datos de registro
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:usuarios'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' asegura que password_confirmation coincida
        ]);

        // Usar el servicio para registrar al usuario
        $user = $this->userService->registerUser([
            'nombre' => $validatedData['nombre'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'], // El servicio se encargará de hashear
        ]);

        // NOTA: Si no quieres que el usuario inicie sesión automáticamente después del registro,
        // puedes eliminar la línea Auth::login($user); y las dos siguientes.
        Auth::login($user); // Autenticar al usuario recién registrado
        $request->session()->regenerate();

        // Redirigir de vuelta a la misma página de registro con un mensaje de éxito
        return redirect()->route('register')->with('status', '¡Usuario registrado correctamente! Ya puedes iniciar sesión.');
    }

    /**
     * Muestra el formulario para solicitar el restablecimiento de contraseña.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envía el enlace de restablecimiento de contraseña al correo electrónico del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validar el correo electrónico
        $request->validate(['email' => 'required|email']);

        try {
            // Intentar enviar el enlace de restablecimiento
            $status = Password::sendResetLink(
                $request->only('email'),
                function ($user, $token) {
                    // Aquí puedes personalizar el envío del correo si lo deseas.
                    // Por defecto, Laravel usa la notificación ResetPassword.
                    // Asegúrate de que tu modelo Usuario implemente CanResetPassword.
                }
            );

            // Manejar la respuesta del envío del enlace
            if ($status == Password::RESET_LINK_SENT) {
                return back()->with('status', __($status));
            }

            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        } catch (\Exception $e) {
            // Capturar cualquier excepción durante el proceso de envío
            Log::error('Error al enviar enlace de restablecimiento para ' . $request->email . ': ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            throw ValidationException::withMessages([
                'email' => ['No se pudo enviar el enlace de restablecimiento. Por favor, inténtalo de nuevo más tarde.'],
            ]);
        }
    }

    /**
     * Muestra el formulario para restablecer la contraseña.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request, ?string $token = null)
    {
        // Pasa el token y el email (si está presente en la URL) a la vista
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Restablece la contraseña del usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function resetPassword(Request $request)
    {
        // Validar los datos del formulario de restablecimiento
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Intentar restablecer la contraseña
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Hashear la nueva contraseña y guardarla
                $user->forceFill([
                    'password' => bcrypt($password), // O usa Hash::make($password)
                ])->setRememberToken(null)->save();

                // Disparar el evento de restablecimiento de contraseña
                Event::dispatch(new PasswordReset($user));
            }
        );

        // Manejar la respuesta del restablecimiento
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
