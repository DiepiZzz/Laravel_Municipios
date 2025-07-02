<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\Usuario; 
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Obtener todos los usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Usuario>
     */
    public function getAllUsers(): Collection
    {
        return Usuario::all();
    }

    /**
     * Obtener un usuario por su ID.
     *
     * @param int $userId El ID del usuario.
     * @return Usuario|null
     */
    public function getUserById(int $userId): ?Usuario
    {
        return Usuario::find($userId);
    }

    /**
     * Crear un nuevo usuario.
     *
     * @param array $userData Los datos del usuario a crear.
     * @return Usuario
     */
    public function createUser(array $userData): Usuario
    {
        
        if (isset($userData['password'])) {
            $userData['password'] = bcrypt($userData['password']);
        }
        return Usuario::create($userData);
    }

    /**
     * Actualizar un usuario existente.
     *
     * @param int $userId El ID del usuario a actualizar.
     * @param array $newDetails Los nuevos detalles del usuario.
     * @return Usuario|null
     */
    public function updateUser(int $userId, array $newDetails): ?Usuario
    {
        $user = Usuario::find($userId);
        if ($user) {
            
            if (isset($newDetails['password'])) {
                $newDetails['password'] = bcrypt($newDetails['password']);
            }
            $user->update($newDetails);
        }
        return $user;
    }

    /**
     * Eliminar un usuario.
     *
     * @param int $userId El ID del usuario a eliminar.
     * @return bool
     */
    public function deleteUser(int $userId): bool
    {
        $user = Usuario::find($userId);
        if ($user) {
            return $user->delete();
        }
        return false;
    }
}
