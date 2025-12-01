<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

// Controladores
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\ServicioSocialPdfController;
use App\Http\Controllers\ClaseRegularizacionController;
use App\Http\Controllers\InscripcionRegularizacionController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ServicioInteresController;

// Modelos
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí registras las rutas de tu API. Estas rutas son cargadas por el
| RouteServiceProvider dentro de un grupo al que se le asigna el middleware "api".
|
*/

/**
 * 🔹 OPCIONAL PERO ÚTIL: catch-all para OPTIONS (preflight CORS)
 * Esto ayuda a que cualquier OPTIONS /api/... responda 204 y pase por HandleCors.
 */
Route::options('/{any}', function (Request $request) {
    return response()->json([], 204);
})->where('any', '.*');


// ==========================================================
//  INSCRIPCIONES REGULARIZACIÓN
// ==========================================================

Route::get('/inscripciones_regularizacion', [InscripcionRegularizacionController::class, 'index']);   // Listar todas
Route::get('/inscripciones_regularizacion/{id}', [InscripcionRegularizacionController::class, 'show']); // Ver una por ID
Route::post('/inscripciones_regularizacion', [InscripcionRegularizacionController::class, 'store']);  // Crear
Route::put('/inscripciones_regularizacion/{id}', [InscripcionRegularizacionController::class, 'update']); // Actualizar
Route::delete('/inscripciones_regularizacion/{id}', [InscripcionRegularizacionController::class, 'destroy']); // Eliminar


// ==========================================================
//  CLASES REGULARIZACIÓN
// ==========================================================

Route::get('/clases_regularizacion', [ClaseRegularizacionController::class, 'index']);
Route::post('/clases_regularizacion', [ClaseRegularizacionController::class, 'store']);
Route::get('/clases_regularizacion/{id}', [ClaseRegularizacionController::class, 'show']);
Route::put('/clases_regularizacion/{id}', [ClaseRegularizacionController::class, 'update']);
Route::delete('/clases_regularizacion/{id}', [ClaseRegularizacionController::class, 'destroy']);

// Clases por profesor: /api/clases-regularizacion/profesor/{id}
Route::get('/clases-regularizacion/profesor/{id}', [ClaseRegularizacionController::class, 'porProfesor']);

// Alumnos inscritos en una clase (para asistencias): /api/clases_regularizacion/{claseId}/alumnos
Route::get('/clases_regularizacion/{claseId}/alumnos', [ClaseRegularizacionController::class, 'alumnos']);


// ==========================================================
//  ASISTENCIAS
// ==========================================================

// ⚠️ Tenías esta ruta duplicada, la dejamos sólo una vez
Route::post('/asistencias/registrar', [AsistenciaController::class, 'registrar']);
// o si tu método bueno es store, cambia la línea de arriba por:
// Route::post('/asistencias/registrar', [AsistenciaController::class, 'store']);


// ==========================================================
//  SERVICIO SOCIAL (PDF u otros)
// ==========================================================

Route::get('/servicio-social', [ServicioSocialPdfController::class, 'index']);
Route::post('/servicio-social', [ServicioSocialPdfController::class, 'store']);
Route::get('/servicio-social/{id}', [ServicioSocialPdfController::class, 'show']);
Route::put('/servicio-social/{id}', [ServicioSocialPdfController::class, 'update']);
Route::delete('/servicio-social/{id}', [ServicioSocialPdfController::class, 'destroy']);


// ==========================================================
//  MATERIAS (CRUD completo)
// ==========================================================

Route::get('/materias', [MateriaController::class, 'index']);
Route::get('/materias/{materia}', [MateriaController::class, 'show']);
Route::post('/materias', [MateriaController::class, 'store']);
Route::put('/materias/{materia}', [MateriaController::class, 'update']);
Route::delete('/materias/{materia}', [MateriaController::class, 'destroy']);


// ==========================================================
//  USUARIOS (CRUD sin token, para pruebas)
// ==========================================================

Route::get('/usuarios', [UserController::class, 'index']);        // Listar todos
Route::post('/usuarios', [UserController::class, 'store']);       // Crear nuevo
Route::get('/usuarios/{id}', [UserController::class, 'show']);    // Ver uno
Route::put('/usuarios/{id}', [UserController::class, 'update']);  // Actualizar
Route::delete('/usuarios/{id}', [UserController::class, 'destroy']); // Eliminar


// ==========================================================
//  AUTENTICACIÓN
// ==========================================================

Route::post('/register', [LoginController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);


// ==========================================================
//  CREAR USUARIOS DE PRUEBA
// ==========================================================

Route::get('/crear-admin', function () {
    $user = User::create([
        'name'     => 'Administrador',
        'email'    => 'admin@a.com',
        'password' => Hash::make('admin123'),
        'rol'      => 'admin',
    ]);
    return response()->json($user);
});

Route::get('/crear-estadias', function () {
    $user = User::create([
        'name'     => 'Coordinador Estadías',
        'email'    => 'estadias@a.com',
        'password' => Hash::make('estadias123'),
        'rol'      => 'admi_estadias',
    ]);
    return response()->json($user);
});


// ==========================================================
//  SERVICIOS DE INTERÉS (ESTADÍAS)
// ==========================================================

Route::post('/servicios-interes', [ServicioInteresController::class, 'store']);
Route::get('/servicios-interes', [ServicioInteresController::class, 'index']);
Route::get('/servicios-interes/resumen', [ServicioInteresController::class, 'resumen']);


// ==========================================================
//  RUTAS PROTEGIDAS CON SANCTUM
// ==========================================================

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data'    => $request->user(),
        ]);
    });
});
