<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

// ✅ Ruta raíz: simple JSON para probar rápido que el backend vive
Route::get('/', function () {
    return response()->json([
        'ok'      => true,
        'message' => 'UniServices backend funcionando 🚀',
    ]);
});

// ✅ Rutas de autenticación (si las usas para el panel web)
Auth::routes();

// ✅ Home (si usas el HomeController del auth de Laravel)
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');


// 🚨 RUTA TEMPORAL SOLO PARA RENDER: CORRER MIGRACIONES
Route::get('/run-migrations-uniservices-123', function () {
    try {
        // ⚠️ Importante el --force porque estás en producción
        Artisan::call('migrate', ['--force' => true]);

        return response()->json([
            'ok'      => true,
            'message' => 'Migraciones ejecutadas correctamente',
            'output'  => Artisan::output(),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'ok'      => false,
            'message' => $e->getMessage(),
        ], 500);
    }
});
