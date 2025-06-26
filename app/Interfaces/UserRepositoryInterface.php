<?php

namespace App\Interfaces;

use App\Models\Usuario; // Importa tu modelo Usuario
use Illuminate\Database\Eloquent\Collection; // Para tipos de retorno

interface UserRepositoryInterface
{
    /**
     * Obtener todos los usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Usuario>
     */
    public function getAllUsers(): Collection;

    /**
     * Obtener un usuario por su ID.
     *
     * @param int $userId El ID del usuario.
     * @return Usuario|null
     */
    public function getUserById(int $userId): ?Usuario;

    /**
     * Crear un nuevo usuario.
     *
     * @param array $userData Los datos del usuario a crear.
     * @return Usuario
     */
    public function createUser(array $userData): Usuario;

    /**
     * Actualizar un usuario existente.
     *
     * @param int $userId El ID del usuario a actualizar.
     * @param array $newDetails Los nuevos detalles del usuario.
     * @return Usuario|null
     */
    public function updateUser(int $userId, array $newDetails): ?Usuario;

    /**
     * Eliminar un usuario.
     *
     * @param int $userId El ID del usuario a eliminar.
     * @return bool
     */
    public function deleteUser(int $userId): bool;
}
