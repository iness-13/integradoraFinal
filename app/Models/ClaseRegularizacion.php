<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\InscripcionRegularizacion;

class ClaseRegularizacion extends Model
{
    use HasFactory;

    protected $table = 'clases_regularizacion';
    protected $primaryKey = 'clase_id';

    public const LUGARES = [
        'CB-01','CB-02','CB-03','CB-04','CB-05','CB-06','CB-07','CB-08','CB-09',
        'CA-01','CA-02','CA-03','CA-04','CA-05','CA-06','CA-07','CA-08','CA-09',
        'CA-SJ',
    ];

    protected $fillable = [
        'nombre_clase',
        'descripcion_c',
        'cupo',
        'horario',
        'lugar',
        'activo',
        'profesor_id',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Profesor asignado a la clase
    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    // Inscripciones (si la usas en otros lados)
    public function inscripciones()
    {
        return $this->hasMany(
            InscripcionRegularizacion::class,
            'clase_id',
            'clase_id'
        );
    }

    // 🔹 Alumnos inscritos en la clase (para asistencias)
    public function alumnos()
    {
        return $this->belongsToMany(
            User::class,
            'inscripciones_regularizacion', // tabla pivote
            'clase_id',                     // FK a clases_regularizacion
            'alumno_id'                     // FK a users
        );
    }
}
