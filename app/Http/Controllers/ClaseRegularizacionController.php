<?php

namespace App\Http\Controllers;

use App\Models\ClaseRegularizacion;
use App\Models\InscripcionRegularizacion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClaseRegularizacionController extends Controller
{
    // 🔹 Obtener clases (todas o solo las de un profesor ?profesor_id=XX)
    public function index(Request $request)
    {
        $query = ClaseRegularizacion::query()
            // 👉 Contar cuántas inscripciones aceptadas tiene cada clase
            ->withCount([
                'inscripciones as aceptados_count' => function ($q) {
                    $q->where('estado', 'aceptada');
                }
            ])
            ->orderBy('clase_id', 'desc');

        // 👇 Si viene profesor_id en la query, filtramos
        if ($request->filled('profesor_id')) {
            $query->where('profesor_id', $request->profesor_id);
        }

        $clases = $query->get();

        return response()->json($clases, 200);
    }

    // 🔹 (opcional) Obtener clases por profesor via /clases-regularizacion/profesor/{id}
    public function porProfesor($id)
    {
        $clases = ClaseRegularizacion::query()
            ->withCount([
                'inscripciones as aceptados_count' => function ($q) {
                    $q->where('estado', 'aceptada');
                }
            ])
            ->where('profesor_id', $id)
            ->orderBy('clase_id', 'desc')
            ->get();

        return response()->json($clases, 200);
    }

    // 🔹 Crear nueva clase
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_clase'   => 'required|string|max:100',
            'cupo'           => 'required|integer|min:1',
            'horario'        => 'required|date_format:H:i',
            'descripcion_c'  => 'nullable|string',
            'lugar'          => [
                'required',
                'string',
                Rule::in(ClaseRegularizacion::LUGARES),
            ],
            'activo'         => 'boolean',
            'profesor_id'    => 'nullable|exists:users,id',
        ]);

        $clase = ClaseRegularizacion::create($validated);

        return response()->json([
            'message' => 'Clase creada correctamente',
            'data'    => $clase,
        ], 201);
    }

    // 🔹 Mostrar clase específica
    public function show($id)
    {
        $clase = ClaseRegularizacion::query()
            ->withCount([
                'inscripciones as aceptados_count' => function ($q) {
                    $q->where('estado', 'aceptada');
                }
            ])
            ->find($id);

        if (!$clase) {
            return response()->json(['message' => 'Clase no encontrada'], 404);
        }

        return response()->json($clase, 200);
    }

    // 🔹 Actualizar clase
    public function update(Request $request, $id)
    {
        $clase = ClaseRegularizacion::find($id);
        if (!$clase) {
            return response()->json(['message' => 'Clase no encontrada'], 404);
        }

        $validated = $request->validate([
            'nombre_clase'   => 'sometimes|required|string|max:100',
            'cupo'           => 'sometimes|required|integer|min:1',
            'horario'        => 'sometimes|required|date_format:H:i',
            'descripcion_c'  => 'sometimes|nullable|string',
            'lugar'          => [
                'sometimes',
                'required',
                'string',
                Rule::in(ClaseRegularizacion::LUGARES),
            ],
            'activo'         => 'sometimes|boolean',
            'profesor_id'    => 'sometimes|nullable|exists:users,id',
        ]);

        $clase->fill($validated);
        $clase->save();

        return response()->json([
            'message' => 'Clase actualizada correctamente',
            'data'    => $clase,
        ], 200);
    }

    // 🔹 Eliminar clase
    public function destroy($id)
    {
        $clase = ClaseRegularizacion::find($id);

        if (!$clase) {
            return response()->json(['message' => 'Clase no encontrada'], 404);
        }

        $clase->delete();

        return response()->json(['message' => 'Clase eliminada correctamente'], 200);
    }

    // 🔹 Alumnos inscritos en una clase (para asistencias y expulsar)
    public function alumnos($claseId)
    {
        $clase = ClaseRegularizacion::find($claseId);

        if (!$clase) {
            return response()->json(['message' => 'Clase no encontrada'], 404);
        }

        // 🔹 Tomamos inscripciones aceptadas de esa clase, cargando al alumno
        $inscripciones = InscripcionRegularizacion::with('alumno')
            ->where('clase_id', $claseId)
            ->where('estado', 'aceptada')
            ->get();

        // 🔹 Regresamos formato amigable para el front
        $data = $inscripciones->map(function ($ins) {
            $alumno = $ins->alumno;

            return [
                'id'             => $ins->alumno_id,
                'inscripcion_id' => $ins->inscripcion_id,
                'nombre'         => $alumno?->name ?? 'Alumno sin nombre',
                'email'          => $alumno?->email,
                'carrera'        => $alumno?->carrera ?? null,
            ];
        })->values();

        return response()->json($data, 200);
    }
}
