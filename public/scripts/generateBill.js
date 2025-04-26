$(document).ready(function() {
    function generateBill() {
        console.log('generateBill function called!');
        let reg_no = sessionStorage.getItem('reg_no');
        // let total = sessionStorage.getItem('total');

        if (reg_no) {
            $.ajax({
                type: 'POST',
                url: 'http://10.100.18.25/si_kris/public/api/medinfras/outpatient/list-patient-bill',
                data: {
                    registrationNo: reg_no,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Data berhasil dikirim ke Kris!')
                    console.log('reg_no: ', reg_no);
                    console.log('Response: ', response);

                    let billNo = response.data[0].PatientBillingNo;

                    sessionStorage.setItem('billing_no', billNo);

                    // if (response.data && response.data.length > 0) {
                    //     let tot_amount = response.data[0].TotalAmount;
                    //     let textMessage = `Total tagihan pasien: ${tot_amount.toLocaleString()}`;

                    //     $('#qrcode').empty();

                    //     let qrCode = new QRCode(document.getElementById("qrcode"), {
                    //         text: textMessage,
                    //         width: 256,
                    //         height: 256,
                    //         colorDark: "#000000",
                    //         colorLight: "#e0ffff",
                    //         correctLevel: QRCode.CorrectLevel.H
                    //     });
                    //     console.log('QR Code generated successfully!');
                    // } else {
                    //     console.log('Data tidak ditemukan dalam response!');
                    // }
                },
                error: function(xhr, status, error) {
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response:', xhr.responseText);
                }
            });
        } else {
            console.log('Data reg_no atau total tidak ditemukan di sessionStorage!');
        }
    }
    
    generateBill();
});