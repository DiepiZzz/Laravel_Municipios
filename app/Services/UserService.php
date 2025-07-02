<?php

namespace App\Services;

use App\Interfaces\UserRepositoryInterface; 
use App\Interfaces\UserServiceInterface;    
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log; 

class UserService implements UserServiceInterface
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Obtener todos los usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Usuario>
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAllUsers();
    }

    /**
     * Obtener un usuario por su ID.
     *
     * @param int $userId El ID del usuario.
     * @return Usuario|null
     */
    public function getUserById(int $userId): ?Usuario
    {
        return $this->userRepository->getUserById($userId);
    }

    /**
     * Registrar un nuevo usuario en el sistema.
     *
     * @param array $userData Los datos del usuario a registrar.
     * @return Usuario
     */
    public function registerUser(array $userData): Usuario
    {
        
        
        
        
        

        Log::info('Intentando registrar un nuevo usuario.', ['username' => $userData['username']]);

        $user = $this->userRepository->createUser($userData);

        if ($user) {
            Log::info('Usuario registrado con éxito.', ['user_id' => $user->id]);
            
            
        }

        return $user;
    }

    /**
     * Actualizar el perfil de un usuario existente.
     *
     * @param int $userId El ID del usuario a actualizar.
     * @param array $newDetails Los nuevos detalles del usuario.
     * @return Usuario|null
     */
    public function updateProfile(int $userId, array $newDetails): ?Usuario
    {
        Log::info('Intentando actualizar perfil de usuario.', ['user_id' => $userId]);
        $user = $this->userRepository->updateUser($userId, $newDetails);

        if ($user) {
            Log::info('Perfil de usuario actualizado.', ['user_id' => $user->id]);
        } else {
            Log::warning('Intento de actualización de perfil para usuario no existente.', ['user_id' => $userId]);
        }

        return $user;
    }

    /**
     * Eliminar la cuenta de un usuario.
     *
     * @param int $userId El ID del usuario a eliminar.
     * @return bool
     */
    public function deleteUserAccount(int $userId): bool
    {
        Log::info('Intentando eliminar cuenta de usuario.', ['user_id' => $userId]);
        $deleted = $this->userRepository->deleteUser($userId);

        if ($deleted) {
            Log::info('Cuenta de usuario eliminada con éxito.', ['user_id' => $userId]);
            
            
        } else {
            Log::warning('Intento de eliminación de cuenta para usuario no existente.', ['user_id' => $userId]);
        }

        return $deleted;
    }
}
