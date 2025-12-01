<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol', // 👈 agregado
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function clasesImpartidas()
{
    return $this->hasMany(ClaseRegularizacion::class, 'profesor_usuario_id');
}

public function inscripciones()
{
    return $this->hasMany(InscripcionRegularizacion::class, 'alumno_usuario_id');
}

}
