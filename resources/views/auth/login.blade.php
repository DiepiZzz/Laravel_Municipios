<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mi Aplicación</title>
    <!-- Incluir Tailwind CSS desde CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilo personalizado para la fuente Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Iniciar Sesión</h2>

        <!-- Mensajes de error de sesión (ej. si el login falla) -->
        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf <!-- Directiva de seguridad de Laravel para formularios -->

            <div class="mb-4">
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">
                    Nombre de Usuario o Email:
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Tu nombre de usuario o email"
                    value="{{ old('username') }}"
                    required
                    autofocus
                >
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                    Contraseña:
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="******************"
                    required
                >
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-gray-700 text-sm">
                    <input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-blue-600 rounded">
                    <span class="ml-2">Recordarme</span>
                </label>
                <a href="{{ route('password.request') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <div class="flex items-center justify-center">
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105"
                >
                    Iniciar Sesión
                </button>
            </div>
        </form>

        <p class="text-center text-gray-600 text-sm mt-6">
            ¿No tienes una cuenta?
            <a href="{{ route('register') }}" class="font-bold text-blue-500 hover:text-blue-800">
                Regístrate aquí
            </a>
        </p>
    </div>
</body>
</html>
