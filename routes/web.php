<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DailyCaptureController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

// TODO: si quieres que al iniciar sesión vaya directo a /captura,
// luego podemos cambiar este dashboard a un redirect.

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // CAPTURA DE PRODUCTIVIDAD
    Route::get('/captura', [DailyCaptureController::class, 'index'])
        ->name('captura.index');

    Route::post('/captura/ward', [DailyCaptureController::class, 'saveWard'])
        ->name('captura.ward.save');

    Route::post('/captura/toco', [DailyCaptureController::class, 'saveToco'])
        ->name('captura.toco.save');

    Route::post('/captura/quirofano', [DailyCaptureController::class, 'saveQuirofano'])
        ->name('captura.quirofano.save');

    Route::post('/captura/outpatient', [DailyCaptureController::class, 'saveOutpatient'])
        ->name('captura.outpatient.save');

    Route::post('/captura/autoclaves', [DailyCaptureController::class, 'saveAutoclaves'])
        ->name('captura.autoclaves.save');

    Route::post('/captura/defunciones', [DailyCaptureController::class, 'saveDefunciones'])
        ->name('captura.defunciones.save');
    
    Route::post('/captura/rrhh', [DailyCaptureController::class, 'saveHumanResources'])
        ->name('captura.rrhh.save');

    Route::post('/captura/nota', [DailyCaptureController::class, 'saveNote'])
    ->name('captura.note.save');


    // INFORMES
    Route::get('/informes', [ReportController::class, 'index'])
        ->name('informes.index');

    Route::post('/informes/generar', [ReportController::class, 'generate'])
        ->name('informes.generate');

    Route::post('/informes/pdf', [ReportController::class, 'pdf'])
        ->name('informes.pdf');
});

    Route::middleware(['auth', 'can:manage-users'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource('users', UserController::class)->except(['show']);
        });