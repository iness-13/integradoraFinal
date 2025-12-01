<?php
// database/migrations/2025_11_28_000000_create_asistencias_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clase_id');
            $table->unsignedBigInteger('alumno_id');
            $table->date('fecha');
            $table->enum('estado', ['A', 'R', 'F']); // Asistió, Retardo, Falta
            $table->timestamps();

            $table->foreign('clase_id')
                ->references('clase_id')->on('clases_regularizacion')
                ->onDelete('cascade');

            $table->foreign('alumno_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->unique(['clase_id', 'alumno_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
