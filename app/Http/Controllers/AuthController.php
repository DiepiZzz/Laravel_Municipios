<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario; 
use Illuminate\Validation\ValidationException;
use App\Interfaces\UserServiceInterface; 
use Illuminate\Support\Facades\Password; 
use Illuminate\Support\Facades\Session; 
use Illuminate\Auth\Events\PasswordReset; 
use Illuminate\Support\Facades\Event; 
use Illuminate\Support\Facades\Log; 

class AuthController extends Controller
{
    private UserServiceInterface $userService;

    
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
        
        $credentials = $request->validate([
            'username' => ['required', 'string'], 
            'password' => ['required', 'string'],
        ]);

        
        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        
        if (Auth::attempt([$fieldType => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate(); 

            
            return redirect()->intended(route('municipios.index'));
        }

        
        throw ValidationException::withMessages([
            'username' => __('auth.failed'), 
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

        
        return redirect()->route('login');
        
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
        
        $validatedData = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:usuarios'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuarios'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        
        $user = $this->userService->registerUser([
            'nombre' => $validatedData['nombre'],
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'], 
        ]);

        
        
        Auth::login($user); 
        $request->session()->regenerate();

        
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
        

        
        $request->validate(['email' => 'required|email']);

        try {
            
            $status = Password::sendResetLink(
                $request->only('email'),
                function ($user, $token) {
                    
                    
                    
                }
            );

            

            
            if ($status == Password::RESET_LINK_SENT) {
                return back()->with('status', __($status));
            }

            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);

        } catch (\Exception $e) {
            
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
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->setRememberToken(null)->save();
                Event::dispatch(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}
