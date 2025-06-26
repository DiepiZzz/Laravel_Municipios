<?php

namespace App\Interfaces;

use App\Models\Municipio; // Importa tu modelo Municipio
use Illuminate\Database\Eloquent\Collection;

interface MunicipioRepositoryInterface
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
     * Crear un nuevo municipio.
     *
     * @param array $municipioData Los datos del municipio a crear.
     * @return Municipio
     */
    public function createMunicipio(array $municipioData): Municipio;

    /**
     * Actualizar un municipio existente.
     *
     * @param int $municipioId El ID del municipio a actualizar.
     * @param array $newDetails Los nuevos detalles del municipio.
     * @return Municipio|null
     */
    public function updateMunicipio(int $municipioId, array $newDetails): ?Municipio;

    /**
     * Eliminar un municipio.
     *
     * @param int $municipioId El ID del municipio a eliminar.
     * @return bool
     */
    public function deleteMunicipio(int $municipioId): bool;
}
