<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipios'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'departamento',
        'pais',
        'alcalde',
        'gobernador',
        'patronoReligioso',
        'numHabitantes',
        'numCasas',
        'numParques',
        'numColegios',
        'descripcion',
    ];
}
