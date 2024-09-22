<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('certificate')->group(function () {
    Route::post('/read', [\App\Http\Controllers\Api\v1\Certification\CertificateReaderController::class, 'uploadCertification'])->middleware('auth:sanctum');
    Route::post('/upload', [\App\Http\Controllers\Api\v1\Certification\CertificateController::class, 'archiveUpload'])->middleware('auth:sanctum');
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\v1\AuthController::class, 'login'])->middleware('guest');
    Route::post('/create', [\App\Http\Controllers\Api\v1\AuthController::class, 'register'])->middleware('auth:sanctum');
    Route::get('/user', [\App\Http\Controllers\Api\v1\AuthController::class, 'currentUser'])->middleware('auth:sanctum');
});

Route::prefix('staff')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'all'])->middleware('auth:sanctum');
    Route::post('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'create'])->middleware('auth:sanctum');
    Route::prefix('{staff}')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'get'])->middleware('auth:sanctum');
        Route::post('/', [\App\Http\Controllers\Api\v1\StaffController::class, 'update'])->middleware('auth:sanctum');
    });
});

Route::prefix('division')->group(function () {
    Route::get('/', [\App\Http\Controllers\Api\v1\DivisionController::class, 'list']);
});
