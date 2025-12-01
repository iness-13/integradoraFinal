<?php

namespace App\Http\Controllers;

use App\Models\InscripcionRegularizacion;
use Illuminate\Http\Request;

class InscripcionRegularizacionController extends Controller
{
    public function index(Request $request)
    {
        $query = InscripcionRegularizacion::with(['alumno', 'clase'])
            ->orderBy('inscripcion_id', 'desc');

        // 🔹 Filtro por estado (pendiente, aceptada, rechazada)
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // 🔹 Filtro por profesor (para el panel del profe)
        if ($request->filled('profesor_id')) {
            $profesorId = $request->profesor_id;
            $query->whereHas('clase', function ($q) use ($profesorId) {
                $q->where('profesor_id', $profesorId);
            });
        }

        // 🔹 Filtro por alumno (para "Mis clases")
        if ($request->filled('alumno_id')) {
            $query->where('alumno_id', $request->alumno_id);
        }

        return response()->json($query->get(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alumno_id'          => 'required|integer',
            'clase_id'           => 'required|integer',
            'asistio'            => 'sometimes|boolean',
            'codigo_confirmacion'=> 'nullable|string|max:10',
            'fecha_inscripcion'  => 'nullable|date',
        ]);

        $existe = InscripcionRegularizacion::where('alumno_id', $validated['alumno_id'])
            ->where('clase_id', $validated['clase_id'])
            ->whereIn('estado', ['pendiente', 'aceptada'])
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Ya existe una solicitud para esta clase por este alumno.'
            ], 409);
        }

        $validated['estado'] = 'pendiente';
        if (empty($validated['fecha_inscripcion'])) {
            $validated['fecha_inscripcion'] = now();
        }

        $inscripcion = InscripcionRegularizacion::create($validated);
        $inscripcion->load(['alumno', 'clase']);

        return response()->json($inscripcion, 201);
    }

    public function update(Request $request, $id)
    {
        $inscripcion = InscripcionRegularizacion::find($id);

        if (!$inscripcion) {
            return response()->json(['message' => 'Inscripción no encontrada'], 404);
        }

        $validated = $request->validate([
            'estado'             => 'sometimes|in:pendiente,aceptada,rechazada',
            'asistio'            => 'sometimes|boolean',
            'codigo_confirmacion'=> 'nullable|string|max:10',
            'fecha_inscripcion'  => 'nullable|date',
        ]);

        $inscripcion->update($validated);
        $inscripcion->load(['alumno', 'clase']);

        return response()->json($inscripcion, 200);
    }

    public function show($id)
    {
        $inscripcion = InscripcionRegularizacion::with(['alumno', 'clase'])->find($id);

        if (!$inscripcion) {
            return response()->json(['message' => 'Inscripción no encontrada'], 404);
        }

        return response()->json($inscripcion, 200);
    }

    public function destroy($id)
    {
        $inscripcion = InscripcionRegularizacion::find($id);

        if (!$inscripcion) {
            return response()->json(['message' => 'Inscripción no encontrada'], 404);
        }

        $inscripcion->delete();

        return response()->json(['message' => 'Inscripción eliminada correctamente'], 200);
    }
}
