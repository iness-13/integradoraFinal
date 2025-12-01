<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioSocialPdf extends Model
{
    protected $table = 'servicio_social_pdf';

    protected $fillable = [
    'empresa_organizacion',
    'nombre_comercial',
    'giro',
    'nombre_contacto',
    'telefono',
    'correo',
    'domicilio',
    'estado',
    'municipio_delegacion',
    'domicilio_extra',
    'nivel_academico',
    'perfil_requerido',
    'horarios',
    'conocimientos_necesarios',
    'modalidad',
    'descripcion',
    'url_ubicacion',
    'imagen'
];

}

