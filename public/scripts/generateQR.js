$(document).ready(function() {
    function generateQR() {
        console.log('generateQR function called!');
        let reg_no = sessionStorage.getItem('registrationNo');
        let total_amount = sessionStorage.getItem('total_amount');
        let billing_no = sessionStorage.getItem('billing_no');
        let medical_record_no = sessionStorage.getItem('RM');
        let payment_method = "021"; // QRIS BRI

        if (reg_no && total_amount && billing_no && medical_record_no) {
            let billing_list = [
                {
                    "billing_no": billing_no,
                    "billing_amount": total_amount
                }
            ];
            $.ajax({
                type: 'POST',
                url: 'http://192.167.4.250/si_kris/public/snap/qris/generate-qr',
                // url: 'https://devkris.droensolobaru.com/snap/qris/generate-qr',
                data: JSON.stringify ({
                    medical_record_no: medical_record_no,
                    registration_no: reg_no,
                    billing_list: billing_list,
                    total_amount: total_amount,
                    payment_method: payment_method
                }),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Data terkirim untuk generate QR!')                   
                    console.log('medical_record_no: ', medical_record_no)                   
                    console.log('registration_no: ', reg_no)                   
                    console.log('billing_list: ', billing_list)                   
                    console.log('total_amount: ', total_amount)
                    console.log('payment_method: ', payment_method)                   

                    if (response) {
                        let qrString = response.data.qrContent;
                        let tot_amount = parseInt(total_amount);
                        let textMessage = `Total tagihan pasien: ${tot_amount.toLocaleString()}`;
                        console.log('qrContent:', response.data.qrContent);

                        $('#qrcode').show();
                        $('#qrcode').empty();

                        let qrCode = new QRCode(document.getElementById("qrcode"), {
                            text: qrString,
                            width: 256,
                            height: 256,
                            colorDark: "#000000",
                            colorLight: "#e0ffff",
                            correctLevel: QRCode.CorrectLevel.M
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