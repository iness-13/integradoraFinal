<?php

// app/Http/Controllers/AsistenciaController.php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    // POST /api/asistencias/registrar
    public function registrar(Request $request)
    {
        $data = $request->validate([
            'clase_id'   => 'required|integer|exists:clases_regularizacion,clase_id',
            'fecha'      => 'required|date',
            'asistencias' => 'required|array|min:1',
            'asistencias.*.alumno_id' => 'required|integer|exists:users,id',
            'asistencias.*.estado'    => 'required|in:A,R,F',
        ]);

        foreach ($data['asistencias'] as $item) {
            Asistencia::updateOrCreate(
                [
                    'clase_id'  => $data['clase_id'],
                    'alumno_id' => $item['alumno_id'],
                    'fecha'     => $data['fecha'],
                ],
                [
                    'estado'    => $item['estado'],
                ]
            );
        }

        return response()->json([
            'message' => 'Asistencias registradas correctamente',
        ], 201);
    }
}
