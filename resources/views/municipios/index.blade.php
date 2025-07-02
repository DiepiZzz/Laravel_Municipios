<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Municipios</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilo personalizado para la fuente Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Estilos para la tabla */
        th, td {
            padding: 0.75rem; /* Ajusta el padding para celdas */
            text-align: left;
            white-space: nowrap; /* Evita que el texto se envuelva */
        }
        th {
            background-color: #374151; /* bg-gray-700 */
            color: #ffffff; /* text-white */
            font-weight: 600; /* font-semibold */
        }
        tr:nth-child(even) {
            background-color: #f3f4f6; /* bg-gray-100 */
        }
        /* Estilos para los botones de acción en la tabla */
        .btn-edit {
            background-color: #3b82f6; /* blue-500 */
            color: white;
            padding: 0.3rem 0.7rem;
            border-radius: 0.375rem; /* rounded-md */
            font-size: 0.875rem; /* text-sm */
            transition: background-color 0.2s ease-in-out;
        }
        .btn-edit:hover {
            background-color: #2563eb; /* blue-600 */
        }
        .btn-delete {
            background-color: #ef4444; /* red-500 */
            color: white;
            padding: 0.3rem 0.7rem;
            border-radius: 0.375rem; /* rounded-md */
            font-size: 0.875rem; /* text-sm */
            transition: background-color 0.2s ease-in-out;
        }
        .btn-delete:hover {
            background-color: #dc2626; /* red-600 */
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-full w-full">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 sm:mb-0">Listado de Municipios</h1>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('municipios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105 text-center">
                    Agregar Municipio
                </a>
                <a href="{{ route('municipios.graficas') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105 text-center">
                    Ver Gráficas
                </a>
                <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 ease-in-out transform hover:scale-105 w-full">
                        Cerrar sesión
                    </button>
                </form>
            </div>
        </div>

        
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

        <div class="overflow-x-auto rounded-lg shadow-md">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-700 text-white">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider rounded-tl-lg">Nombre</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Departamento</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">País</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Alcalde</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Gobernador</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Patrono Religioso</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">N° Habitantes</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">N° Casas</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">N° Parques</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">N° Colegios</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Descripción</th>
                        <th scope="col" class="px-6 py-3 text-xs font-semibold uppercase tracking-wider rounded-tr-lg">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                    @forelse ($municipios as $municipio)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->departamento }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->pais }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->alcalde }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->gobernador }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->patronoReligioso }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->numHabitantes }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->numCasas }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->numParques }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->numColegios }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $municipio->descripcion }}</td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <a href="{{ route('municipios.edit', $municipio->id) }}" class="btn-edit">Editar</a>
                                <form action="{{ route('municipios.destroy', $municipio->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar el municipio {{ $municipio->nombre }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-6 py-4 text-center text-gray-500">No hay municipios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
