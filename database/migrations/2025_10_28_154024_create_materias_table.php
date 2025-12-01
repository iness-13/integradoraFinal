<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMateriasTable extends Migration
{
    public function up()
    {
        Schema::create('materias', function (Blueprint $table) {
            // Usamos id normal pero lo nombramos materia_id para seguir tu modelo
            $table->increments('materia_id');
            $table->string('nombre_m', 30)->unique();
            $table->string('descripcion_m', 255)->nullable();
            $table->timestamps(); // creado_el, actualizado_el (puedes mapear si quieres nombres específicos)
        });
    }

    public function down()
    {
        Schema::dropIfExists('materias');
    }
}
