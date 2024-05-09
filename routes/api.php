<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\SchedulingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {

    Route::prefix('patient')->group(function () {
        Route::post('/create', [PatientController::class, 'store']);
        Route::patch('/update/{id}', [PatientController::class, 'update']);
        Route::get('/list', [PatientController::class, 'index']);
    });

    Route::prefix('dentist')->group(function () {
        Route::post('/create', [DentistController::class, 'store']);
        Route::patch('/update/{id}', [DentistController::class, 'update']);
        Route::get('/list', [DentistController::class, 'index']);
    });

    Route::prefix('scheduling')->group(function () {
        Route::post('/create', [SchedulingController::class, 'store']);
        Route::get('/list', [SchedulingController::class, 'index']);
    });

    Route::prefix('medicalRecord')->group(function () {
        Route::post('/create', [MedicalRecordController::class, 'store']);
        Route::get('/list', [MedicalRecordController::class, 'index']);
        Route::get('/download/{id}', [MedicalRecordController::class, 'downloadMedicalRecord']);
    });
});

Route::post('/login', [AuthController::class, 'login']);
