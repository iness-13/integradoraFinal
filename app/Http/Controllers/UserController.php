<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Si quieres proteger las rutas con auth, descomenta el middleware:
    // public function __construct() { $this->middleware('auth:api'); }

    // Listar todos los usuarios
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();
        return response()->json($users, 200);
    }

    // Crear usuario
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'max:200', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'rol' => ['required', Rule::in(['admin', 'profesor', 'alumno', 'admi_estadias'])],

        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rol'      => $validated['rol'],
        ]);

        return response()->json($user, 201);
    }

    // Ver un usuario
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json($user, 200);
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validated = $request->validate([
            'name'     => ['sometimes', 'string', 'max:150'],
            'email'    => ['sometimes', 'email', 'max:200', Rule::unique('users', 'email')->ignore($id)],
            'password' => ['nullable', 'string', 'min:6'], // opcional
            'rol' => ['sometimes', Rule::in(['admin', 'profesor', 'alumno', 'admi_estadias'])],

        ]);

        if (isset($validated['name'])) {
            $user->name = $validated['name'];
        }
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        if (isset($validated['rol'])) {
            $user->rol = $validated['rol'];
        }

        $user->save();

        return response()->json($user, 200);
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente'], 200);
    }
}
