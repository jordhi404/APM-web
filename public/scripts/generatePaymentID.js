$(document).ready(function() {
    function generatePaymentID() {
        console.log('generatePaymentID function called!');
        let registrationNo = sessionStorage.getItem('registrationNo');
        let billing_no = sessionStorage.getItem('billing_no');
        let shift = "001" // Pagi.
        let cashierGroup = "012"; // Kasir RAJAL.
        let paymentMethod = "021"; // QRIS BRI
        let paymentAmount = sessionStorage.getItem('total_amount');
        let bankCode = "003"; // CIMB NIAGA 9000 (RS), BCA 7256 (YYS).
        let remarks = "Pembayaran melalui CIMB NIAGA Mandiri oleh pasien";
        let referenceNo = "1234567890";

        if (registrationNo && billing_no && paymentAmount) {
            let billList = `${billing_no}-${paymentAmount}`;
            $.ajax({
                type: 'POST',
                url: 'http://192.167.4.250/si_kris/public/api/medinfras/outpatient/pay-bill',
                data: JSON.stringify({
                    registrationNo: registrationNo,
                    billList: billList,
                    shift: shift,
                    cashierGroup: cashierGroup,
                    paymentMethod: paymentMethod,
                    paymentAmount: paymentAmount,
                    bankCode: bankCode,
                    remarks: remarks,
                    referenceNo: referenceNo
                }),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('billList:', billList);
                    console.log('Data berhasil dikirim untuk generate Payment ID!');
                    console.log('Message dari API:', response.message);
                    console.log('PaymentID:', response.data);
                },
                error: function(xhr, status, error) {
                    console.log('billList:', billList);
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response:', xhr.responseText);
                }
            });
        } else {
            console.log('Data tidak ditemukan di sessionStorage!');
        }
    }
    
    generatePaymentID();
});