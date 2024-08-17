<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SchedulingController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {

    Route::prefix('patient')->group(function () {
        Route::post('/create', [PatientController::class, 'store']);
        Route::patch('/update/{id}', [PatientController::class, 'update']);
        Route::get('/list', [PatientController::class, 'index']);
        Route::delete('/delete/{id}', [PatientController::class, 'delete']);
    });

    Route::prefix('dentist')->group(function () {
        Route::post('/create', [DentistController::class, 'store']);
        Route::patch('/update/{id}', [DentistController::class, 'update']);
        Route::get('/list', [DentistController::class, 'index']);
        Route::delete('/delete/{id}', [DentistController::class, 'delete']);
    });

    Route::prefix('scheduling')->group(function () {
        Route::post('/create', [SchedulingController::class, 'store']);
        Route::get('/list', [SchedulingController::class, 'index']);
        Route::post('/cancel/{id}', [SchedulingController::class, 'cancel']);
    });

    Route::prefix('medicalRecord')->group(function () {
        Route::post('/create', [MedicalRecordController::class, 'store']);
        Route::get('/list', [MedicalRecordController::class, 'index']);
        Route::get('/download/{id}', [MedicalRecordController::class, 'downloadMedicalRecord']);
    });

    Route::prefix('payment')->group(function () {
        Route::post('/create', [PaymentController::class, 'store']);
        Route::get('/getPaymentByScheduling/{schedulingId}', [PaymentController::class, 'getPaymentByScheduling']);
        Route::get('/getTotalPaymentByPatient/{patientId}', [PaymentController::class, 'getTotalPaymentByPatient']);
    });
});

Route::post('/login', [AuthController::class, 'login']);