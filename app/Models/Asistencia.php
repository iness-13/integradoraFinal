<?php

// app/Models/Asistencia.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'clase_id',
        'alumno_id',
        'fecha',
        'estado',
    ];

    public function clase()
    {
        return $this->belongsTo(ClaseRegularizacion::class, 'clase_id', 'clase_id');
    }

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }
}
