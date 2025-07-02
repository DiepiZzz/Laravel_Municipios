<?php

namespace App\Http\Controllers;

use App\Interfaces\MunicipioServiceInterface; // Importa la interfaz del Servicio
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MunicipioController extends Controller
{
    private MunicipioServiceInterface $municipioService; // Ahora inyectamos la interfaz del Servicio

    // Inyección de dependencia del servicio
    public function __construct(MunicipioServiceInterface $municipioService)
    {
        $this->municipioService = $municipioService;
    }

    /**
     * Mostrar una lista de municipios.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $municipios = $this->municipioService->getAllMunicipios(); // Llama al método del servicio
        return response()->json([
            'data' => $municipios
        ]);
    }

    /**
     * Almacenar un nuevo municipio.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'departamento' => 'nullable|string|max:255',
            'pais' => 'required|string|max:255',
            'alcalde' => 'nullable|string|max:255',
            'gobernador' => 'nullable|string|max:255',
            'patronoReligioso' => 'nullable|string|max:255',
            'numHabitantes' => 'nullable|integer|min:0',
            'numCasas' => 'nullable|integer|min:0',
            'numParques' => 'nullable|integer|min:0',
            'numColegios' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string',
        ]);

        $municipio = $this->municipioService->createNewMunicipio($validatedData); // Llama al método del servicio
        return response()->json([
            'message' => 'Municipio creado con éxito',
            'data' => $municipio
        ], 201);
    }

    /**
     * Mostrar un municipio específico.
     *
     * @param int $id El ID del municipio.
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $municipio = $this->municipioService->getMunicipioById($id); // Llama al método del servicio

        if (!$municipio) {
            return response()->json(['message' => 'Municipio no encontrado'], 404);
        }

        return response()->json([
            'data' => $municipio
        ]);
    }

    /**
     * Actualizar un municipio específico.
     *
     * @param Request $request
     * @param int $id El ID del municipio.
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validatedData = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'departamento' => 'sometimes|nullable|string|max:255',
            'pais' => 'sometimes|required|string|max:255',
            'alcalde' => 'sometimes|nullable|string|max:255',
            'gobernador' => 'sometimes|nullable|string|max:255',
            'patronoReligioso' => 'sometimes|nullable|string|max:255',
            'numHabitantes' => 'sometimes|nullable|integer|min:0',
            'numCasas' => 'sometimes|nullable|integer|min:0',
            'numParques' => 'sometimes|nullable|integer|min:0',
            'numColegios' => 'sometimes|nullable|integer|min:0',
            'descripcion' => 'sometimes|nullable|string',
        ]);

        $municipio = $this->municipioService->updateMunicipioDetails($id, $validatedData); // Llama al método del servicio

        if (!$municipio) {
            return response()->json(['message' => 'Municipio no encontrado'], 404);
        }

        return response()->json([
            'message' => 'Municipio actualizado con éxito',
            'data' => $municipio
        ]);
    }

    /**
     * Eliminar un municipio específico.
     *
     * @param int $id El ID del municipio.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->municipioService->deleteExistingMunicipio($id); // Llama al método del servicio

        if (!$deleted) {
            return response()->json(['message' => 'Municipio no encontrado'], 404);
        }

        return response()->json(null, 204);
    }
}
