<?php

namespace App\Http\Controllers;

use App\Models\ServicioSocialPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServicioSocialPdfController extends Controller
{
    // 🔹 Listar todos los registros
    public function index()
    {
        return response()->json(ServicioSocialPdf::all(), 200);
    }

    // 🔹 Mostrar un registro por ID
    public function show($id)
    {
        $registro = ServicioSocialPdf::find($id);
        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        return response()->json($registro, 200);
    }

    // 🔹 Crear un nuevo registro (CON IMAGEN REAL)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'empresa_organizacion' => 'required|string|max:80',
            'nombre_comercial' => 'nullable|string|max:80',
            'giro' => 'required|string|max:100',
            'nombre_contacto' => 'nullable|string|max:80',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|string|max:80',
            'domicilio' => 'nullable|string|max:120',
            'estado' => 'nullable|string|max:50',
            'municipio_delegacion' => 'nullable|string|max:50',
            'domicilio_extra' => 'nullable|string|max:120',
            'nivel_academico' => 'nullable|string',
            'perfil_requerido' => 'nullable|string',
            'horarios' => 'nullable|string|max:120',
            'conocimientos_necesarios' => 'nullable|string',
            'modalidad' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'url_ubicacion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // 🔹 Guardar imagen si viene adjunta
        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('servicios', 'public');
        }

        $registro = ServicioSocialPdf::create($validated);
        return response()->json($registro, 201);
    }

    // 🔹 Actualizar un registro existente
    public function update(Request $request, $id)
    {
        $registro = ServicioSocialPdf::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        $validated = $request->validate([
            'empresa_organizacion' => 'required|string|max:80',
            'nombre_comercial' => 'nullable|string|max:80',
            'giro' => 'required|string|max:100',
            'nombre_contacto' => 'nullable|string|max:80',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|string|max:80',
            'domicilio' => 'nullable|string|max:120',
            'estado' => 'nullable|string|max:50',
            'municipio_delegacion' => 'nullable|string|max:50',
            'domicilio_extra' => 'nullable|string|max:120',
            'nivel_academico' => 'nullable|string',
            'perfil_requerido' => 'nullable|string',
            'horarios' => 'nullable|string|max:120',
            'conocimientos_necesarios' => 'nullable|string',
            'modalidad' => 'nullable|string',
            'descripcion' => 'nullable|string',
            'url_ubicacion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        // 🔹 Si viene nueva imagen, eliminar la anterior
        if ($request->hasFile('imagen')) {
            if ($registro->imagen && Storage::disk('public')->exists($registro->imagen)) {
                Storage::disk('public')->delete($registro->imagen);
            }

            $validated['imagen'] = $request->file('imagen')->store('servicios', 'public');
        }

        $registro->update($validated);

        return response()->json($registro, 200);
    }

    // 🔹 Eliminar un registro
    public function destroy($id)
    {
        $registro = ServicioSocialPdf::find($id);
        if (!$registro) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        // 🔹 eliminar imagen si existe
        if ($registro->imagen && Storage::disk('public')->exists($registro->imagen)) {
            Storage::disk('public')->delete($registro->imagen);
        }

        $registro->delete();
        return response()->json(['message' => 'Registro eliminado correctamente'], 200);
    }
}
