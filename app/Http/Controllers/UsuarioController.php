<?php

namespace App\Http\Controllers;

use App\Interfaces\UserServiceInterface; 
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UsuarioController extends Controller
{
    private UserServiceInterface $userService; 

    
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Mostrar una lista de usuarios.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers(); 
        return response()->json([
            'data' => $users
        ]);
    }

    /**
     * Almacenar un nuevo usuario.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'username' => 'required|unique:usuarios',
            'password' => 'required|min:6',
            'nombre' => 'required',
            'email' => 'required|email|unique:usuarios',
        ]);

        $user = $this->userService->registerUser($validatedData); 
        return response()->json([
            'message' => 'Usuario creado con éxito',
            'data' => $user
        ], 201);
    }

    /**
     * Mostrar un usuario específico.
     *
     * @param int $id El ID del usuario.
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id); 

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Actualizar un usuario específico.
     *
     * @param Request $request
     * @param int $id El ID del usuario.
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $validatedData = $request->validate([
            'username' => 'sometimes|required|unique:usuarios,username,' . $id,
            'password' => 'sometimes|nullable|min:6',
            'nombre' => 'sometimes|required',
            'email' => 'sometimes|required|email|unique:usuarios,email,' . $id,
        ]);

        $user = $this->userService->updateProfile($id, $validatedData); 

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json([
            'message' => 'Usuario actualizado con éxito',
            'data' => $user
        ]);
    }

    /**
     * Eliminar un usuario específico.
     *
     * @param int $id El ID del usuario.
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->userService->deleteUserAccount($id); 

        if (!$deleted) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json(null, 204);
    }
}
