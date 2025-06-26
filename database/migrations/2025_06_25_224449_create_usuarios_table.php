<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); // ID autoincremental, clave primaria
            $table->string('username')->unique(); // Nombre de usuario, debe ser único
            $table->string('password'); // Contraseña
            $table->string('nombre'); // Nombre completo
            $table->string('email')->unique(); // Correo electrónico, debe ser único
            $table->timestamps(); // Crea automáticamente columnas 'created_at' y 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
