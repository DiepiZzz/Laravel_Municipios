<?php

namespace App\Interfaces;

use App\Models\Municipio;
use Illuminate\Database\Eloquent\Collection;

interface MunicipioServiceInterface
{
    /**
     * Obtener todos los municipios.
     *
     * @return \Illuminate\Database\Eloquent\Collection<Municipio>
     */
    public function getAllMunicipios(): Collection;

    /**
     * Obtener un municipio por su ID.
     *
     * @param int $municipioId El ID del municipio.
     * @return Municipio|null
     */
    public function getMunicipioById(int $municipioId): ?Municipio;

    /**
     * Crear un nuevo municipio con la lógica de negocio asociada.
     *
     * @param array $municipioData Los datos del municipio a crear.
     * @return Municipio
     */
    public function createNewMunicipio(array $municipioData): Municipio;

    /**
     * Actualizar los detalles de un municipio existente.
     *
     * @param int $municipioId El ID del municipio a actualizar.
     * @param array $newDetails Los nuevos detalles del municipio.
     * @return Municipio|null
     */
    public function updateMunicipioDetails(int $municipioId, array $newDetails): ?Municipio;

    /**
     * Eliminar un municipio.
     *
     * @param int $municipioId El ID del municipio a eliminar.
     * @return bool
     */
    public function deleteExistingMunicipio(int $municipioId): bool;
}
