<?php

namespace App\Http\Controllers;

use App\Events\cardPayment;
use App\Events\PaidPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\dummy_data;
use App\Models\list_harga;
use App\Models\TagihanDummy;
use App\Models\patient;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Log;

class dummyController extends Controller
{
    /******************************** TERHUBUNG KE DB POSTGRE APM ********************************/
    // public function getPatientInfo($RM)
    // {
    //     $data_pgsql = dummy_data::where('MR', $RM)->first();

    //     if(!$data_pgsql) {
    //         return response()->json([
    //             'message' => 'Data not found'
    //         ], 404);
    //     }

    //     return response()->json($data_pgsql);
    // }

    /******************************** TAGIHAN DUMMY ********************************/
    // public function getPatientBill($RM) {
    //     $tagihan = TagihanDummy::where('MR', $RM)
    //             ->with(['harga', 'pasien'])
    //             ->get();

    //     // Jika tidak ada tagihan, kembalikan respons error
    //     if ($tagihan->isEmpty()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Tagihan tidak ditemukan untuk pasien ini'
    //         ], 404);
    //     }

    //     $totalTagihan = $tagihan->sum(function($item) {
    //         return ($item->harga ? $item->harga->Biaya : 0) * $item->Qty;
    //     });

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $tagihan,
    //         'total' => $totalTagihan,
    //     ]);
    // }

    /****************************************************************************************************************************************/

    /******************************** TERHUBUNG KE DB MEDIN ********************************/
    public function getPatientInfo(Request $request)
    {
        $RM = $request->input('RM');
        $dob = $request->input('dob');

        $data_pasien = Patient::where('MedicalNo', $RM)
                        ->whereDate('DateOfBirth', $dob)
                        ->first();

        if(!$data_pasien) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pasien tidak ditemukan. Cek kembali nomor rekam medis dan tanggal lahir.'
            ], 404);
        } else {
            $latest_registration = $data_pasien->registrations()
                                    ->whereNotIn('GCRegistrationStatus', ['X020^006', 'X020^007'])
                                    ->where('GCCustomerType', 'X004^999')                                  
                                    ->orderByDesc('RegistrationDate')
                                    ->first();

            $RegistrationNo = $latest_registration->RegistrationNo ?? null;

            return response()->json([
                'status' => 'success',
                'data' => $data_pasien, 
                'reg_no' => $RegistrationNo
            ]);
        }
    }

    /* QR Page */
    public function showQrPage()
    {
        return view('pages.qr-page');
    }

    /* Debit card payment page */
    public function showCardPage()
    {
        return view('pages.card-page');
    }

    // public function showTfPage()
    // {
    //     return view('pages.tf-page');
    // }

    /* Halaman metode bayar */
    public function getPaymentMethod() {
        return view('pages.metode-bayar');
    }

    /* Beranda */
    public function showFrontPage()
    {
        return view('pages.front-page');
    }

    /* Halaman input RM dan dob */ 
    public function showIndex()
    {
        return view('pages.index');
    }

    /* Halaman rincian tagihan */
    public function showDetails()
    {
        return view('pages.details');
    }

    /* Halaman pembayaran selesai */ 
    public function showPaymentSuccess()
    {
        return view('pages.payment-success');
    }

    /* Handling callback response pembayaran */ 
    public function handleCallback(Request $request) {
        // Log::info('Received callback from SI-KRIS');

        $data = $request->all();

        // Log::info('Request data: ', $data);
        $theirSignature = $request->header('X-Signature'); // dari SI-KRIS
        $secret = env('SI_KRIS_SECRET');

        // Hitung signature sendiri
        $expectedSignature = hash_hmac('sha256', json_encode($data), $secret);

        if (!hash_equals($expectedSignature, $theirSignature)) {
            // Log::warning('Invalid signature.');
            return response()->json([
                'status' => 'unauthorized',
                'message' => 'Invalid signature',
                'sent_signature' => $theirSignature,
                'expected_signature' => $expectedSignature,
            ], 403);
        }

        $payload = $data;

        $responseMessage = $payload['transactionStatusDesc'] ?? null;

        if ($responseMessage !== 'success') {
            return response()->json([
                'status' => 'error',
                'message' => 'Response yang didapat: ' . $responseMessage,
            ], 403);
        } else {
            $responseReffNo = $payload['referenceNo'] ?? null;
    
            if ($responseReffNo) {
                // Log::info("Payment success!");
                // Log::info('Payload: ', $payload);
                // Log::info('Reference No: ' . $responseReffNo);
                event(new PaidPayment($responseReffNo, $payload));
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Callback received successfully',
            'payload' => $payload,
            'test' => 'Payment successful',
        ], 200);
    }

    public function cardPaymentCallback(Request $request) {
        // Log::info('Received card payment callback from SI-KRIS');

        $data = $request->all();

        // Log::info('Request data: ', $data);
        $KRISCardSignature = $request->header('X-Signature'); // dari SI-KRIS
        $card_secret = env('SIKRIS_CARD_SECRET');

        // Hitung signature sendiri
        $expectedCardSignature = hash_hmac('sha256', json_encode($data), $card_secret);

        if (!hash_equals($expectedCardSignature, $KRISCardSignature)) {
            // Log::warning('Invalid signature.');
            return response()->json([
                'status' => 'unauthorized',
                'message' => 'Invalid signature',
                'sent_signature' => $KRISCardSignature,
                'expected_signature' => $expectedCardSignature,
            ], 403);
        }

        $CardRes = $data;

        $responseMessage = $CardRes['msg'] ?? null;

        if ($responseMessage !== 'transaksi berhasil') {
            return response()->json([
                'status' => 'error',
                'message' => 'Response yang didapat: ' . $responseMessage,
            ], 403);
        } else {
            $responseTrxId = $CardRes['transaction_id'] ?? null;
    
            if ($responseTrxId) {
                // Log::info("Payment success!");
                // Log::info('CardRes: ', $CardRes);
                // Log::info('Transaction ID: ' . $responseTrxId);
                event(new cardPayment($responseTrxId, $CardRes));
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Callback received successfully',
            'CardRes' => $CardRes,
            'test' => 'Payment successful',
        ], 200);
    }
}