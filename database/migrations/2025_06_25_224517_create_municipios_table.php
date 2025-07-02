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
         Schema::create('municipios', function (Blueprint $table) {
            $table->id(); 
            $table->string('nombre');
            $table->string('departamento')->nullable(); 
            $table->string('pais');
            $table->string('alcalde')->nullable();
            $table->string('gobernador')->nullable();
            $table->string('patronoReligioso')->nullable();
            $table->integer('numHabitantes')->nullable();
            $table->integer('numCasas')->nullable();
            $table->integer('numParques')->nullable();
            $table->integer('numColegios')->nullable();
            $table->text('descripcion')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
