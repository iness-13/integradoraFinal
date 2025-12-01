<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicio_social_pdf', function (Blueprint $table) {
            $table->id();

            // 🏢 Datos de la empresa u organización
            $table->string('empresa_organizacion', 80);
            $table->string('nombre_comercial', 80)->nullable();
            $table->string('giro', 100);
            $table->string('nombre_contacto', 80)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 80)->nullable();

            // 📍 Dirección
            $table->string('domicilio', 120)->nullable();
            $table->string('estado', 50)->nullable();
            $table->string('municipio_delegacion', 50)->nullable();
            $table->string('domicilio_extra', 120)->nullable();

            // 🎓 Requisitos
            $table->enum('nivel_academico', ['TSU', 'Ingeniería'])->nullable();
            $table->text('perfil_requerido')->nullable();

            // 🕓 Detalles del servicio social
            $table->string('horarios', 120)->nullable();
            $table->text('conocimientos_necesarios')->nullable();
            $table->enum('modalidad', ['Presencial', 'Híbrida', 'Virtual'])->default('Presencial');
            $table->text('descripcion')->nullable();

            // 📎 Extras
            $table->string('url_ubicacion')->nullable();

            // 📸 Imagen (ruta del storage)
            $table->string('imagen')->nullable(); // aquí guardamos la ruta tipo storage/servicios/imagen.png

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicio_social_pdf');
    }
};
