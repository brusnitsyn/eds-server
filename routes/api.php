<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return \App\Http\Resources\Auth\UserResource::make($request->user());
});

Route::get('/crypt', [\App\Http\Controllers\Api\v1\AuthController::class, 'crypt'])->middleware('guest');

Route::prefix('aci')->group(function () {
    Route::get('/staff', [\App\Http\Controllers\Api\ACI\ACIController::class, 'staff'])->middleware('guest');
});

Route::prefix('certificate')->group(function () {
    Route::post('/read', [\App\Http\Controllers\Api\v1\Certification\CertificateReaderController::class, 'uploadCertification'])->middleware('auth:sanctum');
    Route::post('/upload', [\App\Http\Controllers\Api\v1\Certification\CertificateController::class, 'archiveUpload'])->middleware('auth:sanctum');
    Route::post('/download', [\App\Http\Controllers\Api\v1\Certification\CertificateController::class, 'downloadCert'])->middleware('auth:sanctum');
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\v1\AuthController::class, 'login'])->middleware('guest');
    Route::post('/create', [\App\Http\Controllers\Api\v1\AuthController::class, 'register'])->middleware('auth:sanctum');
    Route::get('/user', [\App\Http\Controllers\Api\v1\AuthController::class, 'currentUser'])->middleware('auth:sanctum');
});

Route::prefix('staff')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'all'])->middleware('auth:sanctum');
    Route::post('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'create'])->middleware('auth:sanctum');
    Route::get('/export', [\App\Http\Controllers\Api\v1\StaffController::class, 'importExcel'])->middleware('auth:sanctum');
    Route::prefix('{staff}')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'get'])->middleware('auth:sanctum');
        Route::post('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'update'])->middleware('auth:sanctum');
        Route::delete('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'delete'])->middleware('auth:sanctum');
        Route::prefix('integrate')->group(function () {
            Route::post('/', [\App\Http\Controllers\Api\v1\StaffIntegratedController::class, 'create'])->middleware('auth:sanctum');
            Route::prefix('{staffIntegrate}')->group(function () {
                Route::delete('/', [\App\Http\Controllers\Api\v1\StaffIntegratedController::class, 'delete'])->middleware('auth:sanctum');
                Route::put('/', [\App\Http\Controllers\Api\v1\StaffIntegratedController::class, 'update'])->middleware('auth:sanctum');
            });
        });
    });
});

Route::prefix('journal')->group(function () {
    Route::prefix('fall')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\v1\PacientFallEventController::class, 'all']);
        Route::post('/', [\App\Http\Controllers\Api\v1\PacientFallEventController::class, 'store']);
    });
});

Route::prefix('division')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\v1\DivisionController::class, 'list']);
});

Route::prefix('admin')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\v1\Admin\UserController::class, 'create'])
            ->middleware(['auth:sanctum', 'abilities:user:create']);
        Route::prefix('{user}')->group(function () {
            Route::prefix('token')->group(function () {
                Route::get('/', [\App\Http\Controllers\Api\v1\Admin\TokenController::class, 'get'])
                    ->middleware(['auth:sanctum', 'abilities:adm:token:read']);
                Route::post('/', [\App\Http\Controllers\Api\v1\Admin\TokenController::class, 'create'])
                    ->middleware(['auth:sanctum', 'abilities:adm:token:create']);
                Route::prefix('{token}')->group(function () {

                });
            });
        });
    });
});
