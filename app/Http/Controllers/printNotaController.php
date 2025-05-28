<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class printNotaController extends Controller
{
    public function printPaymentBill(Request $request)
    {
        $registrationNo = $request->input('registrationNo');

        if (!$registrationNo) {
            return response()->json([
                'error' => 'Registration number is required'
            ], 400);
        }

        // Log::info("Info dari printNota controller, registrationNo: " . $registrationNo);

        $data = DB::connection('medinfras_dev')
            ->select("
                SELECT
                    r.RegistrationNo,
                    p.FullName,
                    im.ItemName1 AS ItemName,
                    dt.LineAmount AS HargaAkhir,
                    pb.TotalAmount AS TagihanTotal,
                    pb.PaymentID
                FROM Patient p
                LEFT JOIN Registration r ON p.MRN = r.MRN
                LEFT JOIN PatientBill pb ON r.RegistrationID = pb.RegistrationID
                LEFT JOIN PatientChargesHd pbd ON pb.PatientBillingID = pbd.PatientBillingID
                LEFT JOIN PatientChargesDt dt ON pbd.TransactionID = dt.TransactionID
                LEFT JOIN ItemMaster im ON dt.ItemID = im.ItemID 
                WHERE r.GCRegistrationStatus NOT IN ('X020^006', 'X020^007')
                AND pbd.GCTransactionStatus NOT IN ('X121^999')
                AND r.GCCustomerType = 'X004^999'
                AND dt.IsDeleted = 0
                AND pb.PaymentID IS NOT NULL
                AND r.RegistrationNo = ?
                ORDER BY pbd.TransactionDate DESC
            ", [$registrationNo]);
        
        return response()->json([
            'data' => $data,
        ]);
    }
}
