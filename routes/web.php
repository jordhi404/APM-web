<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dummyController;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('layouts.contoh');
});

Route::get('/index', function () {
    return view('pages.index');
});

Route::get('/welcome', function () {
    return view('pages.front-page');
})->name('welcome');

Route::get('/details', function () {
    return view('pages.details');
});

Route::get('/metode-bayar', [dummyController::class, 'getPaymentMethod'])->name('metode-bayar');

Route::get('/qr-payment', [dummyController::class, 'showQrPage'])->name('qr-payment');

Route::get('/tf-payment', function () {
    return view('pages.tf-page');
});

Route::get('/cash-payment', function () {
    return view('pages.cash-page');
});

Route::get('/payment-success', function () {
    return view('pages.payment-success');
});

Route::post('/api/patients-info', [dummyController::class, 'getPatientInfo'])->name('patientInfo');
// Route::get('/api/patients-info/{RM}', [dummyController::class, 'getPatientInfo'])->name('patientInfoGet');

// Route::get('/api/patients-bill/{RM}/{dob}', [dummyController::class, 'getPatientBill'])->name('patientBill');
Route::post('/api/patients-bill', [dummyController::class, 'getPatientBill'])->name('patientBill');

// Route::get('/generate-qr/{RegistrationNo}', [dummyController::class, 'generateQrCode'])->where('RegistrationNo', '.*')->name('generateQrCode');

/* Route untuk pengujian koneksi ke SQL Server. */

    // Route::get('/test-sqlserver', function () {
    //     try {
    //         DB::connection('sqlsrv')->getPdo();
    //         echo "Connected successfully to the database ms sql server!";
    //     } catch (\Exception $e) {
    //         die("Could not connect to the database. Error: " . $e->getMessage());
    //     }
    // }); 
