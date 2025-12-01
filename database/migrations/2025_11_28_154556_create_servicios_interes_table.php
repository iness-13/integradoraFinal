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
        Schema::create('servicios_interes', function (Blueprint $table) {
            $table->id();

            // FK al usuario con rol alumno
            $table->unsignedBigInteger('alumno_id');

            // FK al servicio (tabla servicio_social_pdf)
            $table->unsignedBigInteger('servicio_id');

            $table->timestamps();

            // 🔹 alumno_id -> users.id
            $table->foreign('alumno_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // 🔹 servicio_id -> servicio_social_pdf.id  (AQUÍ ESTABA EL PROBLEMA)
            $table->foreign('servicio_id')
                ->references('id')
                ->on('servicio_social_pdf')
                ->onDelete('cascade');

            // para que un alumno no pueda marcar la misma empresa 2 veces
            $table->unique(['alumno_id', 'servicio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios_interes');
    }
};
