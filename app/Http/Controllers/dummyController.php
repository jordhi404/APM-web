<?php

namespace App\Http\Controllers;

use App\Events\cardPayment;
use App\Events\PaidPayment;
use App\Events\CardCancel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\dummy_data;
use App\Models\list_harga;
use App\Models\TagihanDummy;
use App\Models\patient;
use App\Models\PatientChargesHd;
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
                                    ->with(['patientBills' => function ($query) {
                                                $query->whereNull('PaymentID')
                                                    ->orderByDesc('BillingDate')
                                                    ->limit(1);
                                        }])
                                    ->first();

            $RegistrationNo = $latest_registration->RegistrationNo ?? null;
            $firstBill = $latest_registration->patientBills->first();
            $patientBillingNo = $firstBill?->PatientBillingNo;


            return response()->json([
                'status' => 'success',
                'data' => $data_pasien, 
                'reg_no' => $RegistrationNo,
                'bill_no' => $patientBillingNo,
            ]);
        }
    }

    public function getPatientBill(Request $request)
    {
        $existedBillNo = $request->input('existedBillNo');

        $charges = PatientChargesHd::with([
            'healthcareServiceUnit.serviceUnit', // akses ServiceUnitMaster lewat HealthcareServiceUnit
            'patientBill.registration'        // akses Registration lewat PatientBilling
        ])
            ->whereHas('patientBill', function ($query) use ($existedBillNo) {
                $query->where('PatientBillingNo', $existedBillNo);
        })
            ->orderByDesc('TransactionDate')
            ->get()
            ->map(function ($pch) {
                return [
                    'RegistrationNo'       => $pch->patientBill->registration->RegistrationNo ?? null,
                    'PatientBillingNo'     => $pch->patientBill->PatientBillingNo ?? null,
                    'TransactionNo'        => $pch->TransactionNo,
                    'TransactionDate'      => $pch->TransactionDate,
                    'TransactionTime'      => $pch->TransactionTime,
                    'ServiceUnitCode'      => $pch->healthcareServiceUnit->serviceUnit->ServiceUnitCode ?? null,
                    'ServiceUnitName'      => $pch->healthcareServiceUnit->serviceUnit->ServiceUnitName ?? null,
                    'TotalPatientAmount'   => (fmod($pch->TotalPatientAmount, 1) == 0.0) 
                                                ? intval($pch->TotalPatientAmount) 
                                                : floatval($pch->TotalPatientAmount),
                    'TotalPayerAmount'     => (fmod($pch->TotalPayerAmount, 1) == 0.0) 
                                                ? intval($pch->TotalPayerAmount) 
                                                : floatval($pch->TotalPayerAmount),
                ];
            });

        return response()->json([
            'satus' => 'success',
            'message' => 'Data tagihan berhasil diambil',
            'data' => $charges,
        ], 200);
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

    public function showPaymentCanceled()
    {
        return view('pages.canceled');
    }

    /* HANDLE CALLBACK PEMBAYARAN VIA QRIS */ 
    public function handleCallback(Request $request) {
        Log::info('Received callback from SI-KRIS');

        $data = $request->all();

        Log::info('Request data: ', $data);
        $theirSignature = $request->header('X-Signature'); // dari SI-KRIS
        $secret = env('SI_KRIS_SECRET');

        // Hitung signature sendiri
        $expectedSignature = hash_hmac('sha256', json_encode($data), $secret);

        if (!hash_equals($expectedSignature, $theirSignature)) {
            Log::warning('Invalid signature.');
            return response()->json([
                'status' => 'unauthorized',
                'message' => 'Invalid signature',
                'sent_signature' => $theirSignature,
                'expected_signature' => $expectedSignature,
            ], 403);
        }

        $payload = $data;

        $responseMessage = $payload['status'] ?? null;

        if ($responseMessage !== 'success') {
            return response()->json([
                'status' => 'error',
                'message' => 'Response yang didapat: ' . $responseMessage,
            ], 403);
        } else {
            $responseReffNo = $payload['reference_no'] ?? null;
    
            if ($responseReffNo) {
                Log::info("Payment success!");
                Log::info('Payload: ', $payload);
                Log::info('Reference No: ' . $responseReffNo);
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

    /* HANDLE CALLBACK PEMBAYARAN VIA KARTU DEBIT (EDC) */
    public function cardPaymentCallback(Request $request) {
        Log::info('Received card payment callback from SI-KRIS');

        $data = $request->all();

        Log::info('Request data: ', $data);
        $KRISCardSignature = $request->header('X-Signature'); // dari SI-KRIS
        $card_secret = env('SI_KRIS_SECRET');

        // Hitung signature sendiri
        $expectedCardSignature = hash_hmac('sha256', json_encode($data), $card_secret);

        if (!hash_equals($expectedCardSignature, $KRISCardSignature)) {
            Log::warning('Invalid signature.');
            return response()->json([
                'status' => 'unauthorized',
                'message' => 'Invalid signature',
                'sent_signature' => $KRISCardSignature,
                'expected_signature' => $expectedCardSignature,
            ], 403);
        }

        $CardRes = $data;

        $responseStatus = $CardRes['status'] ?? null;
        $responseReferenceNo = $CardRes['reference_no'] ?? null;
        $responseMessage = $CardRes['msg'] ?? null;

        if ($responseStatus == 'failed') {
            if ($responseReferenceNo == 'N/A') {
                $responseTrxId = $CardRes['transaction_id'];
                event(new CardCancel($responseTrxId, $CardRes));

                return response()->json([
                    'status' => 'error',
                    'response_status' => $responseStatus,
                    'reference_no' => $responseReferenceNo,
                    'message' => $responseMessage . ', Transaksi dibatalkan.',
                ], 403);
            } else {
                return response()->json([
                    'status' => 'error',
                    'response_status' => $responseStatus,
                    'reference_no' => $responseReferenceNo,
                    'message' => $responseMessage . ', PIN yang dimasukkan salah.',
                ], 403);
            }
        } elseif ($responseStatus == 'success') {
            $responseTrxId = $CardRes['transaction_id'] ?? null;
    
            if ($responseTrxId) {
                Log::info("Payment success!");
                Log::info('CardRes: ', $CardRes);
                Log::info('Transaction ID: ' . $responseTrxId);
                event(new cardPayment($responseTrxId, $CardRes));
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'TrxiD yang diterima: ' . $responseTrxId,
                    'data' => $CardRes,
                ], 403);
            }
        } else {
            return response()->json([
                'status' => $responseStatus,
                'message' => 'Kejadian tidak terduga',
                'data' => $CardRes,
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Callback received successfully',
            'CardRes' => $CardRes,
            'test' => 'Payment successful',
        ], 200);
    }

    // private const QRIS_API_URL = 'https://devkris.droensolobaru.com/api/snap/qris/generate-qr';
    // /**
    //  * Generate QRIS dengan data default (untuk testing)
    //  */
    // public function generateQrisTest()
    // {
    //     $testData = [
    //         'medical_record_no' => '34-56-34-45',
    //         'registration_no' => 'OPR/20250531/00001',
    //         'billing_list' => [
    //             [
    //                 'billing_no' => 'OPB/20250531/00001',
    //                 'billing_amount' => '90'
    //             ],
    //             // [
    //             //     'billing_no' => 'OPB/20250531/00002',
    //             //     'billing_amount' => '60'
    //             // ]
    //         ],
    //         'total_amount' => '150',
    //         'payment_method' => '021'
    //     ];

    //     try {
    //         $response = Http::timeout(30)
    //             ->acceptJson()
    //             ->post(self::QRIS_API_URL, $testData);

    //         Log::info('QRIS Test API Request', [
    //             'url' => self::QRIS_API_URL,
    //             'data' => $testData,
    //             'response_status' => $response->status(),
    //             'response_body' => $response->body()
    //         ]);

    //         if ($response->successful()) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => 'QRIS QR code generated successfully (test)',
    //                 'data' => $response->json()
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Failed to generate QRIS QR code (test)',
    //                 'error' => $response->body(),
    //                 'status_code' => $response->status()
    //             ], $response->status());
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('QRIS Test API Error', [
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ]);

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred while generating QRIS QR code (test)',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}