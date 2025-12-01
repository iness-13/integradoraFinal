<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 👀 Solo intenta agregar la columna si NO existe
        if (!Schema::hasColumn('clases_regularizacion', 'profesor_id')) {
            Schema::table('clases_regularizacion', function (Blueprint $table) {
                // Campo que guarda el ID del profesor
                $table->unsignedBigInteger('profesor_id')->nullable()->after('lugar');

                // Llave foránea hacia la tabla users
                $table->foreign('profesor_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('set null'); // si se borra el profesor, la clase queda sin profesor
            });
        }
    }

    public function down(): void
    {
        // 👀 Solo intenta borrar si la columna existe
        if (Schema::hasColumn('clases_regularizacion', 'profesor_id')) {
            Schema::table('clases_regularizacion', function (Blueprint $table) {
                $table->dropForeign(['profesor_id']);
                $table->dropColumn('profesor_id');
            });
        }
    }
};
