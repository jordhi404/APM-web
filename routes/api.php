<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dummyController;
use App\Http\Controllers\printNotaController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/payment/bank/si-kris-callback', [dummyController::class, 'handleCallback'])->name('si-kris-callback');

Route::post('/payment/bank/kris-card-callback', [dummyController::class, 'cardPaymentCallback'])->name('kris-card-callback');

Route::post('/patients-info', [dummyController::class, 'getPatientInfo'])->name('patientInfo');

Route::post('/patients-bill', [dummyController::class, 'getPatientBill'])->name('patientBill');

Route::post('/print-bill', [printNotaController::class, 'printPaymentBill'])->name('print-bill');

// Route::post('/qris-test', [dummyController::class, 'generateQrisTest'])->name('qris-test');