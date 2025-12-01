<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones_regularizacion', function (Blueprint $table) {
            $table->id('inscripcion_id');

            $table->unsignedBigInteger('alumno_id')->nullable();
            $table->unsignedBigInteger('clase_id')->nullable();
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');

            $table->boolean('asistio')->default(false);
            $table->string('codigo_confirmacion', 10)->nullable();
            $table->timestamp('fecha_inscripcion')->useCurrent();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones_regularizacion');
    }
};
