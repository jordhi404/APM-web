<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\dummy_data;
use App\Models\list_harga;
use App\Models\TagihanDummy;
use App\Models\patient;
use Barryvdh\Debugbar\Facades\Debugbar;

use function Pest\Laravel\get;

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
                        ->whereHas('registrations.patientBills', function($query) {
                            $query->whereNull('PaymentID')
                                ->whereHas('registration', function ($subQuery) {
                                    $subQuery->whereNotIn('GCRegistrationStatus', ['X020^006'])
                                            ->where('GCCustomerType', 'X004^999');
                                });
                        })
                        ->with([
                            'registrations' => function ($regQuery) {
                                $regQuery->where('GCCustomerType', 'X004^999')
                                        ->whereNotIn('GCRegistrationStatus', ['X020^006', 'X020^007'])
                                        ->with(['patientBills' => function ($billQuery) {
                                            $billQuery->whereNull('PaymentID')
                                                    ->with(['chargesHd' => function ($chargesHdQuery) {
                                                        $chargesHdQuery ->with(['chargeDetails' => function ($detailsQuery) {
                                                                            $detailsQuery->where('IsDeleted', 0)
                                                                                        ->with('item');
                                                                        }])
                                                                        ->whereNotIn('GCTransactionStatus', ['X121^999'])
                                                                        ->orderByDesc('TransactionDate');
                                                    }]);
                                        }]);
                            }
                        ]) 
                        ->first();

        if(!$data_pasien) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pasien tidak ditemukan. Cek kembali nomor rekam medis dan tanggal lahir.'
            ], 404);
        }

        return response()->json($data_pasien);
    }

    /******************************** TAGIHAN DARI MEDIN ********************************/
    public function getPatientBill(Request $request) {
        $RM = $request->input('RM');
        $dob = $request->input('dob');

        $patient = Patient::where('MedicalNo', $RM)
                    ->whereDate('DateOfBirth', $dob)
                    ->whereHas('registrations.patientBills', function($query) {
                        $query->whereNull('PaymentID')
                            ->whereHas('registration', function ($subQuery) {
                                $subQuery->whereNotIn('GCRegistrationStatus', ['X020^006'])
                                        ->where('GCCustomerType', 'X004^999');
                            });
                    })
                    ->with([
                        'registrations' => function ($regQuery) {
                            $regQuery->where('GCCustomerType', 'X004^999')
                                    ->whereNotIn('GCRegistrationStatus', ['X020^006', 'X020^007'])
                                    ->with(['patientBills' => function ($billQuery) {
                                        $billQuery->whereNull('PaymentID')
                                                ->with(['chargesHd' => function ($chargesHdQuery) {
                                                    $chargesHdQuery ->with(['chargeDetails' => function ($detailsQuery) {
                                                                        $detailsQuery->where('IsDeleted', 0)
                                                                                    ->with('item');
                                                                    }])
                                                                    ->whereNotIn('GCTransactionStatus', ['X121^999'])
                                                                    ->orderByDesc('TransactionDate');
                                                }]);
                                    }]);
                        }
                    ]) 
                    ->first();

        if (!$patient) {
            return response()->json([
                'status' => 'kosong',
                'message' => 'Pasien tidak ditemukan atau tidak ada tagihan aktif untuk pasien ini.'
            ], 404);
        }

        $tagihan = collect();
        // $RegistrationNo = $patient->Registrations[0]->RegistrationNo;

        foreach ($patient->registrations as $reg) {
            foreach ($reg->patientBills as $bill) {
                foreach ($bill->chargesHd as $hd) {
                    foreach ($hd->chargeDetails as $dt) {
                        $tagihan->push([
                            'RegistrationNo' => $reg->RegistrationNo,
                            'TransactionNo' => $hd->TransactionNo,
                            'Layanan' => $dt->item->ItemName1,
                            'Banyak' => $dt->ChargedQuantity,
                            'HargaSatuan' => $dt->Tariff,
                            'HargaAkhir' => $dt->LineAmount,
                            'Tanggal' => $hd->TransactionDate,
                        ]);
                    }
                }
            }
        }

        $totalTagihan = $tagihan->sum('HargaAkhir');
        $RegistrationNo = $tagihan->first()['RegistrationNo'] ?? null;
        $matchedRegistration = $patient->registrations->firstWhere('RegistrationNo', $RegistrationNo);

        session(['registration_no' => $RegistrationNo]);

        return response()->json([
            'status' => 'success',
            'pasien' => [
                'MedicalNo' => $patient->MedicalNo,
                'Nama' => $patient->FullName,
                'DateOfBirth' => $patient->DateOfBirth,
            ],
            'registration' =>[
                'RegistrationID' => $matchedRegistration?->RegistrationID,
                'RegistrationDate' => $matchedRegistration?->RegistrationDate,
                'RegistrationTime' => $matchedRegistration?->RegistrationTime,
                'RegistrationNo' => $RegistrationNo,
            ],
            'data' => $tagihan,
            'total' => $totalTagihan,
        ]);
    }

    /******************************** QR GENERATOR ********************************/
    public function showQrPage()
    {
        // try {
        //     // $RegistrationNo = session('registration_no');
        //     // $RegistrationNo = "OTR/20240901/00011";

        //     // if (!$RegistrationNo) {
        //     //     Debugbar::error('RegistrationNo not found in session.');
        //     // }

        //     // $strRegistrationNo = str_replace('/', '_', $RegistrationNo);
        //     $url = "http://10.100.18.25/si_kris/public/api/medinfras/outpatient/lock-transaction";

        //     $response = Http::post($url);

        //     if ($response->successful()) {
        //         $statusMessage = $response->json();

        //         $message = $statusMessage['message'] ?? 'Tidak ada pesan dari server.';           
        //     } else {
        //         Debugbar::error('HTTP request failed with status: ' . $response->status(), [
        //             'url' => $url,
        //             'response' => $response->body(),
        //         ]);
        //         $message = 'Gagal mendapatkan pesan dari server.';
        //     }
        // } catch (\Exception $e) {
        //     $message = 'Terjadi kesalahan saat menghubungi server: ' . $e->getMessage();
        // }

        // $qrCode = QrCode::size(100)->generate($message);

        // Debugbar::info('URL dikirim: ' . $url);
        // Debugbar::info('HTTP status: ' . $response->status());
        // Debugbar::info('Response body: ' . $response->body());

        return view('pages.qr-page');
    }

    public function getPaymentMethod() {
        Debugbar::info('RegistrationNo: ' . session('registration_no'));
        return view('pages.metode-bayar');
    }
}