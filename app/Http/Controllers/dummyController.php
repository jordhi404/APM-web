<?php

namespace App\Http\Controllers;

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

        // $data_pasien = Patient::where('MedicalNo', $RM)
        //                 ->whereDate('DateOfBirth', $dob)
        //                 ->whereHas('registrations.patientBills', function($query) {
        //                     $query->whereNull('PaymentID')
        //                         ->whereHas('registration', function ($subQuery) {
        //                             $subQuery->whereNotIn('GCRegistrationStatus', ['X020^006'])
        //                                     ->where('GCCustomerType', 'X004^999');
        //                         });
        //                 })
        //                 ->with([
        //                     'registrations' => function ($regQuery) {
        //                         $regQuery->where('GCCustomerType', 'X004^999')
        //                                 ->whereNotIn('GCRegistrationStatus', ['X020^006', 'X020^007'])
        //                                 ->with(['patientBills' => function ($billQuery) {
        //                                     $billQuery->whereNull('PaymentID')
        //                                             ->with(['chargesHd' => function ($chargesHdQuery) {
        //                                                 $chargesHdQuery ->with(['chargeDetails' => function ($detailsQuery) {
        //                                                                     $detailsQuery->where('IsDeleted', 0)
        //                                                                                 ->with('item');
        //                                                                 }])
        //                                                                 ->whereNotIn('GCTransactionStatus', ['X121^999'])
        //                                                                 ->orderByDesc('TransactionDate');
        //                                             }]);
        //                                 }]);
        //                     }
        //                 ]) 
        //                 ->first();

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

    /******************************** TAGIHAN DARI MEDIN ********************************/
    // public function getPatientBill(Request $request) {
    //     $RM = $request->input('RM');
    //     $dob = $request->input('dob');

    //     $patient = Patient::where('MedicalNo', $RM)
    //                 ->whereDate('DateOfBirth', $dob)
    //                 ->whereHas('registrations.patientBills', function($query) {
    //                     $query->whereNull('PaymentID')
    //                         ->whereHas('registration', function ($subQuery) {
    //                             $subQuery->whereNotIn('GCRegistrationStatus', ['X020^006'])
    //                                     ->where('GCCustomerType', 'X004^999');
    //                         });
    //                 })
    //                 ->with([
    //                     'registrations' => function ($regQuery) {
    //                         $regQuery->where('GCCustomerType', 'X004^999')
    //                                 ->whereNotIn('GCRegistrationStatus', ['X020^006', 'X020^007'])
    //                                 ->with(['patientBills' => function ($billQuery) {
    //                                     $billQuery->whereNull('PaymentID')
    //                                             ->with(['chargesHd' => function ($chargesHdQuery) {
    //                                                 $chargesHdQuery ->with(['chargeDetails' => function ($detailsQuery) {
    //                                                                     $detailsQuery->where('IsDeleted', 0)
    //                                                                                 ->with('item');
    //                                                                 }])
    //                                                                 ->whereNotIn('GCTransactionStatus', ['X121^999'])
    //                                                                 ->orderByDesc('TransactionDate');
    //                                             }]);
    //                                 }]);
    //                     }
    //                 ]) 
    //                 ->first();

    //     if (!$patient) {
    //         return response()->json([
    //             'status' => 'kosong',
    //             'message' => 'Pasien tidak ditemukan atau tidak ada tagihan aktif untuk pasien ini.'
    //         ], 404);
    //     }

    //     $tagihan = collect();
    //     // $RegistrationNo = $patient->Registrations[0]->RegistrationNo;

    //     foreach ($patient->registrations as $reg) {
    //         foreach ($reg->patientBills as $bill) {
    //             foreach ($bill->chargesHd as $hd) {
    //                 foreach ($hd->chargeDetails as $dt) {
    //                     $tagihan->push([
    //                         'RegistrationNo' => $reg->RegistrationNo,
    //                         'TransactionNo' => $hd->TransactionNo,
    //                         'Layanan' => $dt->item->ItemName1,
    //                         'Banyak' => $dt->ChargedQuantity,
    //                         'HargaSatuan' => $dt->Tariff,
    //                         'HargaAkhir' => $dt->LineAmount,
    //                         'Tanggal' => $hd->TransactionDate,
    //                     ]);
    //                 }
    //             }
    //         }
    //     }

    //     $totalTagihan = $tagihan->sum('HargaAkhir');
    //     $RegistrationNo = $tagihan->first()['RegistrationNo'] ?? null;
    //     $matchedRegistration = $patient->registrations->firstWhere('RegistrationNo', $RegistrationNo);

    //     session(['registration_no' => $RegistrationNo]);

    //     return response()->json([
    //         'status' => 'success',
    //         'pasien' => [
    //             'MedicalNo' => $patient->MedicalNo,
    //             'Nama' => $patient->FullName,
    //             'DateOfBirth' => $patient->DateOfBirth,
    //         ],
    //         'registration' =>[
    //             'RegistrationID' => $matchedRegistration?->RegistrationID,
    //             'RegistrationDate' => $matchedRegistration?->RegistrationDate,
    //             'RegistrationTime' => $matchedRegistration?->RegistrationTime,
    //             'RegistrationNo' => $RegistrationNo,
    //         ],
    //         'data' => $tagihan,
    //         'total' => $totalTagihan,
    //     ]);
    // }

    /******************************** QR PAGE ********************************/
    public function showQrPage()
    {
        return view('pages.qr-page');
    }

    public function showCardPage()
    {
        return view('pages.card-page');
    }

    public function showTfPage()
    {
        return view('pages.tf-page');
    }

    public function getPaymentMethod() {
        return view('pages.metode-bayar');
    }

    public function showFrontPage()
    {
        return view('pages.front-page');
    }

    public function showIndex()
    {
        return view('pages.index');
    }

    public function showDetails()
    {
        return view('pages.details');
    }

    public function showPaymentSuccess()
    {
        return view('pages.payment-success');
    }

    public function handleCallback(Request $request) {
        Log::info('Received callback from SI-KRIS');

        $data = $request->getContent();

        $payload = json_decode($data, true);

        $responseMessage = $payload['responseMessage'] ?? null; // Pastikan kembali response dari bank, mungkin bisa ganti dengan responseCode.

        // $secretKey = env('SI_KRIS_SECRET');

        if ($responseMessage !== 'Successfull') {
            return response()->json([
                'status' => 'error',
                'message' => 'Response yang didapat: ' . $responseMessage,
            ], 403);
        } else {
            $responseReffId = $payload['additionalInfo']['reffId'] ?? null;
    
            if ($responseReffId) {
                Log::info("Payment successfull!");
                Log::info('Payload: ', $payload);
                event(new PaidPayment($responseReffId, $payload));
            }
        }

        $payload = json_decode($data, true);

        $status = $payload['status'] ?? null;

        if($status == 'PAID') {
            Log::info('Payment successful');
            Log::info('Payload: ', $payload);
            // event(new paymentSuccess($payload['status']));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Callback received successfully',
            'payload' => $payload,
            'test' => 'Payment successful',
        ], 200);
    }
}