<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dummyController;
use Illuminate\Support\Facades\DB;

// Route::get('/', function () {
//     return view('layouts.contoh');
// });

Route::get('/index', [dummyController::class, 'showIndex'])->name('index');

Route::get('/', [dummyController::class, 'showFrontPage'])->name('welcome');

Route::get('/details', [dummyController::class, 'showDetails'])->name('details');

Route::get('/metode-bayar', [dummyController::class, 'getPaymentMethod'])->name('metode-bayar');

// Route::get('/qr-payment/{registrationNo}', [dummyController::class, 'showQrPage'])->name('qr-payment');
Route::get('/qr-payment', [dummyController::class, 'showQrPage'])->name('qr-payment');

Route::get('/tf-payment', function () {
    return view('pages.tf-page');
});

Route::get('/cash-payment', function () {
    return view('pages.cash-page');
});

Route::get('/payment-success', [dummyController::class, 'showPaymentSuccess'])->name('payment-success');

// Route::post('/payment/bank/si-kris-callback', [dummyController::class, 'handleCallback'])->name('si-kris-callback');

// Route::post('/api/patients-info', [dummyController::class, 'getPatientInfo'])->name('patientInfo');

// Route::post('/api/patients-bill', [dummyController::class, 'getPatientInfo'])->name('patientBill');

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
