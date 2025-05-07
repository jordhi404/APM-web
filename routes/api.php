<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dummyController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/payment/bank/si-kris-callback', [dummyController::class, 'handleCallback'])->name('si-kris-callback');

Route::post('/patients-info', [dummyController::class, 'getPatientInfo'])->name('patientInfo');

Route::post('/patients-bill', [dummyController::class, 'getPatientInfo'])->name('patientBill');
