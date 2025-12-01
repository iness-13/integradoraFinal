<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table = 'materias';
    protected $primaryKey = 'materia_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // 👇 muy importante
    protected $fillable = [
        'nombre_m',
        'descripcion_m',
    ];
}
