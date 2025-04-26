$(document).ready(function() {
    function generateQR() {
        console.log('generateQR function called!');
        let reg_no = sessionStorage.getItem('reg_no');
        let total = sessionStorage.getItem('total');
        let billing_no = sessionStorage.getItem('billing_no');
        let medical_record_no = sessionStorage.getItem('RM');

        if (reg_no && total && billing_no && medical_record_no) {
            $.ajax({
                type: 'POST',
                url: 'http://10.100.18.25/si_kris/public/snap/qris/generate-qr',
                data: {
                    medical_record_no: medical_record_no,
                    registration_no: reg_no,
                    billing_no: billing_no,
                    total_amount: total,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Data terkirim untuk generate QR!')                   

                    if (response.data && response.data.length > 0) {
                        let tot_amount = response.data[0].TotalAmount;
                        // let textMessage = `Total tagihan pasien: ${tot_amount.toLocaleString()}`;

                        $('#qrcode').empty();

                        let qrCode = new QRCode(document.getElementById("qrcode"), {
                            // text: textMessage,
                            width: 256,
                            height: 256,
                            colorDark: "#000000",
                            colorLight: "#e0ffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });
                        console.log('QR Code generated successfully!');
                    } else {
                        console.log('Ada masalah generate QR!');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response:', xhr.responseText);
                }
            });
        } else {
            console.log('Data tidak ditemukan di sessionStorage!');
        }
    }
    
    generateQR();
});