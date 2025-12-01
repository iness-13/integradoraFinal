<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    // 🔓 Sin autenticación ni middleware
    public function __construct()
    {
        // Middleware eliminado: todos los endpoints son públicos
    }

    // ✅ Obtener todas las materias
    public function index()
    {
        try {
            $materias = Materia::orderBy('nombre_m')->get();
            return response()->json($materias, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener las materias',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Crear nueva materia
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_m' => 'required|string|max:255',
            'descripcion_m' => 'nullable|string|max:255', // 👈 nombre correcto del campo
        ]);

        $materia = Materia::create($validated);
        return response()->json($materia, 201);
    }

    // ✅ Mostrar una materia por ID
    public function show(Materia $materia)
    {
        return response()->json($materia);
    }

    // ✅ Actualizar materia
    public function update(Request $request, Materia $materia)
    {
        $validated = $request->validate([
            'nombre_m' => 'required|string|max:255',
            'descripcion_m' => 'nullable|string|max:255', // 👈 corregido igual que arriba
        ]);

        $materia->update($validated);
        return response()->json($materia);
    }

    // ✅ Eliminar materia
    public function destroy(Materia $materia)
    {
        $materia->delete();
        return response()->json(['message' => 'Materia eliminada correctamente'], 200);
    }
}
