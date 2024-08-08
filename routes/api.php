<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('certificate')->group(function () {
    Route::post('/read', [\App\Http\Controllers\Api\v1\Certification\CertificateReaderController::class, 'uploadCertification']);
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\v1\AuthController::class, 'login']);
    Route::post('/create', [\App\Http\Controllers\Api\v1\AuthController::class, 'register']);
    Route::get('/user', [\App\Http\Controllers\Api\v1\AuthController::class, 'currentUser']);
});

Route::prefix('staff')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'all'])->middleware('auth:sanctum');
    Route::post('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'create'])->middleware('auth:sanctum');
    Route::prefix('{staff}')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'get'])->middleware('auth:sanctum');
    });
});

Route::prefix('division')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\v1\DivisionController::class, 'list']);
});
