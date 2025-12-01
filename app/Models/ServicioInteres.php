<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicioInteres extends Model
{
    protected $table = 'servicios_interes';

    protected $fillable = [
        'alumno_id',
        'servicio_id'
    ];

    // Un interés pertenece a un alumno (user)
    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    // Un interés pertenece a un servicio
    public function servicio()
    {
        return $this->belongsTo(ServicioSocialPdf::class, 'servicio_id');
    }
}
