<?php

namespace App\Http\Controllers;

use App\Models\ServicioInteres;
use Illuminate\Http\Request;

class ServicioInteresController extends Controller
{
    /**
     * Registrar interés de un alumno en un servicio
     */
    public function store(Request $request)
    {
        // 👀 Importante: usamos las tablas reales: users y servicio_social_pdf
        $validated = $request->validate([
            'alumno_id'   => 'required|exists:users,id',
            'servicio_id' => 'required|exists:servicio_social_pdf,id',
        ]);

        try {
            // Evitamos duplicados
            $interes = ServicioInteres::firstOrCreate([
                'alumno_id'   => $validated['alumno_id'],
                'servicio_id' => $validated['servicio_id'],
            ]);

            return response()->json([
                'ok'      => true,
                'message' => 'Interés registrado correctamente',
                'data'    => $interes,
            ], 201);
        } catch (\Throwable $e) {
            \Log::error('Error al registrar interés', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'ok'      => false,
                'message' => 'Error interno al registrar el interés',
            ], 500);
        }
    }

    /**
     * (Opcional) lista plana de intereses
     */
    public function index()
    {
        $intereses = ServicioInteres::with(['servicio', 'alumno'])->get();

        return response()->json($intereses);
    }

    /**
     * 🔹 REPORTE AGRUPADO POR EMPRESA
     */
    public function resumen()
    {
        // cargamos servicio y alumno
        $intereses = ServicioInteres::with(['servicio', 'alumno'])->get();

        // agrupamos por servicio_id (empresa)
        $grouped = $intereses->groupBy('servicio_id')->map(function ($items, $servicioId) {
            $servicio = $items->first()->servicio;

            $empresaNombre = $servicio?->empresa_organizacion ?? 'Empresa sin nombre';
            $giro          = $servicio?->giro ?? null;

            // listita de alumnos
            $alumnos = $items->map(function ($it) {
                $alumno = $it->alumno;

                return [
                    'alumno_id' => $alumno?->id,
                    'nombre'    => $alumno?->name ?? 'Alumno sin nombre',
                    // si no tienes campo carrera en users, esto nunca truena
                    'carrera'   => $alumno->carrera ?? null,
                ];
            })->values()->all();

            return [
                'servicio_id'       => (int) $servicioId,
                'empresa'           => $empresaNombre,
                'giro'              => $giro,
                'total_interesados' => count($alumnos),
                'alumnos'           => $alumnos,
            ];
        })->values(); // para que el JSON sea un array simple

        return response()->json($grouped);
    }
}
