<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscripcionRegularizacion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones_regularizacion';
    protected $primaryKey = 'inscripcion_id';

    protected $fillable = [
        'alumno_id',
        'clase_id',
        'estado',
        'asistio',
        'codigo_confirmacion',
        'fecha_inscripcion',
    ];

    protected $casts = [
        'asistio'           => 'boolean',
        'fecha_inscripcion' => 'datetime',
    ];

    // 🔹 Relación con el alumno (users)
    public function alumno()
    {
        return $this->belongsTo(\App\Models\User::class, 'alumno_id');
    }

    // 🔹 Relación con la clase
    public function clase()
    {
        return $this->belongsTo(
            \App\Models\ClaseRegularizacion::class,
            'clase_id',
            'clase_id'
        );
    }
}
