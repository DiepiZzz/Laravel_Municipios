<?php

namespace App\Services;

use App\Interfaces\MunicipioRepositoryInterface; 
use App\Interfaces\MunicipioServiceInterface;    
use App\Models\Municipio;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log; 

class MunicipioService implements MunicipioServiceInterface
{
    private MunicipioRepositoryInterface $municipioRepository;

    public function __construct(MunicipioRepositoryInterface $municipioRepository)
    {
        $this->municipioRepository = $municipioRepository;
    }

    /**
     * Obtener todos los municipios.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Municipio>
     */
    public function getAllMunicipios(): Collection
    {
        return $this->municipioRepository->getAllMunicipios();
    }

    /**
     * Obtener un municipio por su ID.
     *
     * @param int $municipioId El ID del municipio.
     * @return Municipio|null
     */
    public function getMunicipioById(int $municipioId): ?Municipio
    {
        return $this->municipioRepository->getMunicipioById($municipioId);
    }

    /**
     * Crear un nuevo municipio con la lógica de negocio asociada.
     *
     * @param array $municipioData Los datos del municipio a crear.
     * @return Municipio
     */
    public function createNewMunicipio(array $municipioData): Municipio
    {
        Log::info('Intentando crear un nuevo municipio.', ['nombre' => $municipioData['nombre']]);
        $municipio = $this->municipioRepository->createMunicipio($municipioData);

        if ($municipio) {
            Log::info('Municipio creado con éxito.', ['municipio_id' => $municipio->id]);
            
            
        }

        return $municipio;
    }

    /**
     * Actualizar los detalles de un municipio existente.
     *
     * @param int $municipioId El ID del municipio a actualizar.
     * @param array $newDetails Los nuevos detalles del municipio.
     * @return Municipio|null
     */
    public function updateMunicipioDetails(int $municipioId, array $newDetails): ?Municipio
    {
        Log::info('Intentando actualizar detalles de municipio.', ['municipio_id' => $municipioId]);
        $municipio = $this->municipioRepository->updateMunicipio($municipioId, $newDetails);

        if ($municipio) {
            Log::info('Detalles de municipio actualizados.', ['municipio_id' => $municipio->id]);
        } else {
            Log::warning('Intento de actualización de detalles para municipio no existente.', ['municipio_id' => $municipioId]);
        }

        return $municipio;
    }

    /**
     * Eliminar un municipio.
     *
     * @param int $municipioId El ID del municipio a eliminar.
     * @return bool
     */
    public function deleteExistingMunicipio(int $municipioId): bool
    {
        Log::info('Intentando eliminar municipio.', ['municipio_id' => $municipioId]);
        $deleted = $this->municipioRepository->deleteMunicipio($municipioId);

        if ($deleted) {
            Log::info('Municipio eliminado con éxito.', ['municipio_id' => $municipioId]);
            
            
        } else {
            Log::warning('Intento de eliminación para municipio no existente.', ['municipio_id' => $municipioId]);
        }

        return $deleted;
    }
}
