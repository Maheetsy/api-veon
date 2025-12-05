

<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProviderController;
// Rutas Públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Rutas Protegidas (Requieren Login)
Route::middleware('firebase.auth')->group(function () {
    
    // Perfil (Accesible para todos los roles autenticados)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
    // Rutas de Proveedores (Solo Admin y Vendedor)
    Route::middleware('role:admin,vendedor')->group(function () {
        Route::apiResource('providers', ProviderController::class);
        // Añade aquí rutas de ventas/cotizaciones
    });
    // Rutas Solo Admin (Ejemplo)
    Route::middleware('role:admin')->group(function () {
        // Route::apiResource('users', UserController::class);
        // Route::delete('/providers/{id}', [ProviderController::class, 'destroy']); // Si solo admin puede borrar
    });
});