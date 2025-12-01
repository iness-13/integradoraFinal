<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clases_regularizacion', function (Blueprint $table) {
            $table->id('clase_id');
            $table->string('nombre_clase');

            $table->text('descripcion_c')->nullable();

            // cupo: sólo enteros positivos
            $table->unsignedInteger('cupo')->default(20);

            // horario: tipo time (hora, sin fecha)
            $table->time('horario');

            // lugar: sólo opciones específicas (enum)
            $table->enum('lugar', [
                'CB-01',
                'CB-02',
                'CB-03',
                'CB-04',
                'CB-05',
                'CB-06',
                'CB-07',
                'CB-08',
                'CB-09',
                'CA-01',
                'CA-02',
                'CA-03',
                'CA-04',
                'CA-05',
                'CA-06',
                'CA-07',
                'CA-08',
                'CA-09',
                'CA-SJ',
            ]);

            // 👇 NUEVO: profesor asignado (de la tabla users)
            $table->unsignedBigInteger('profesor_id');
            $table->foreign('profesor_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            // si prefieres que al borrar profesor se quede la clase sin profe:
            // ->onDelete('set null');

            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases_regularizacion');
    }
};
