<?php

namespace App\Repositories;

use App\Interfaces\MunicipioRepositoryInterface;
use App\Models\Municipio; 
use Illuminate\Database\Eloquent\Collection;

class MunicipioRepository implements MunicipioRepositoryInterface
{
    /**
     * Obtener todos los municipios.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Municipio>
     */
    public function getAllMunicipios(): Collection
    {
        return Municipio::all();
    }

    /**
     * Obtener un municipio por su ID.
     *
     * @param int $municipioId El ID del municipio.
     * @return Municipio|null
     */
    public function getMunicipioById(int $municipioId): ?Municipio
    {
        return Municipio::find($municipioId);
    }

    /**
     * Crear un nuevo municipio.
     *
     * @param array $municipioData Los datos del municipio a crear.
     * @return Municipio
     */
    public function createMunicipio(array $municipioData): Municipio
    {
        return Municipio::create($municipioData);
    }

    /**
     * Actualizar un municipio existente.
     *
     * @param int $municipioId El ID del municipio a actualizar.
     * @param array $newDetails Los nuevos detalles del municipio.
     * @return Municipio|null
     */
    public function updateMunicipio(int $municipioId, array $newDetails): ?Municipio
    {
        $municipio = Municipio::find($municipioId);
        if ($municipio) {
            $municipio->update($newDetails);
        }
        return $municipio;
    }

    /**
     * Eliminar un municipio.
     *
     * @param int $municipioId El ID del municipio a eliminar.
     * @return bool
     */
    public function deleteMunicipio(int $municipioId): bool
    {
        $municipio = Municipio::find($municipioId);
        if ($municipio) {
            return $municipio->delete();
        }
        return false;
    }
}
