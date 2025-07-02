<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Municipio</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilo personalizado para la fuente Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Editar Municipio: {{ $municipio->nombre }}</h2>

        
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

        <form method="POST" action="{{ route('municipios.update', $municipio->id) }}">
            @csrf
            @method('PUT') {{-- Método PUT para actualizaciones --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-4">
                    <label for="nombre" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('nombre', $municipio->nombre) }}" required>
                </div>
                <div class="mb-4">
                    <label for="departamento" class="block text-gray-700 text-sm font-bold mb-2">Departamento:</label>
                    <input type="text" id="departamento" name="departamento" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('departamento', $municipio->departamento) }}">
                </div>
                <div class="mb-4">
                    <label for="pais" class="block text-gray-700 text-sm font-bold mb-2">País:</label>
                    <input type="text" id="pais" name="pais" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('pais', $municipio->pais) }}" required>
                </div>
                <div class="mb-4">
                    <label for="alcalde" class="block text-gray-700 text-sm font-bold mb-2">Alcalde:</label>
                    <input type="text" id="alcalde" name="alcalde" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('alcalde', $municipio->alcalde) }}">
                </div>
                <div class="mb-4">
                    <label for="gobernador" class="block text-gray-700 text-sm font-bold mb-2">Gobernador:</label>
                    <input type="text" id="gobernador" name="gobernador" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('gobernador', $municipio->gobernador) }}">
                </div>
                <div class="mb-4">
                    <label for="patronoReligioso" class="block text-gray-700 text-sm font-bold mb-2">Patrono Religioso:</label>
                    <input type="text" id="patronoReligioso" name="patronoReligioso" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('patronoReligioso', $municipio->patronoReligioso) }}">
                </div>
                <div class="mb-4">
                    <label for="numHabitantes" class="block text-gray-700 text-sm font-bold mb-2">N° Habitantes:</label>
                    <input type="number" id="numHabitantes" name="numHabitantes" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('numHabitantes', $municipio->numHabitantes) }}" min="0">
                </div>
                <div class="mb-4">
                    <label for="numCasas" class="block text-gray-700 text-sm font-bold mb-2">N° Casas:</label>
                    <input type="number" id="numCasas" name="numCasas" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('numCasas', $municipio->numCasas) }}" min="0">
                </div>
                <div class="mb-4">
                    <label for="numParques" class="block text-gray-700 text-sm font-bold mb-2">N° Parques:</label>
                    <input type="number" id="numParques" name="numParques" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('numParques', $municipio->numParques) }}" min="0">
                </div>
                <div class="mb-4">
                    <label for="numColegios" class="block text-gray-700 text-sm font-bold mb-2">N° Colegios:</label>
                    <input type="number" id="numColegios" name="numColegios" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" value="{{ old('numColegios', $municipio->numColegios) }}" min="0">
                </div>
                <div class="mb-4 col-span-1 md:col-span-2">
                    <label for="descripcion" class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" rows="4" class="shadow appearance-none border rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descripcion', $municipio->descripcion) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-center space-x-4 mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105">
                    Actualizar Municipio
                </button>
                <a href="{{ route('municipios.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>
