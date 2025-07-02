<?php

namespace App\Interfaces;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;

interface UserServiceInterface
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
     * Registrar un nuevo usuario en el sistema.
     * Aquí podría ir lógica de negocio adicional como envío de emails de bienvenida.
     *
     * @param array $userData Los datos del usuario a registrar.
     * @return Usuario
     */
    public function registerUser(array $userData): Usuario;

    /**
     * Actualizar el perfil de un usuario existente.
     *
     * @param int $userId El ID del usuario a actualizar.
     * @param array $newDetails Los nuevos detalles del usuario.
     * @return Usuario|null
     */
    public function updateProfile(int $userId, array $newDetails): ?Usuario;

    /**
     * Eliminar la cuenta de un usuario.
     * Aquí podría ir lógica de negocio adicional como eliminar datos relacionados.
     *
     * @param int $userId El ID del usuario a eliminar.
     * @return bool
     */
    public function deleteUserAccount(int $userId): bool;
}
