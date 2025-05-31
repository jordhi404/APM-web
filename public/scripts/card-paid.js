$(document).ready(function() {
    function cardPayment() {
        console.log('cardPayment function called!');
        let reg_no = sessionStorage.getItem('registrationNo');
        let total_amount = sessionStorage.getItem('total_amount');
        let billing_no = sessionStorage.getItem('billing_no');
        let medical_record_no = sessionStorage.getItem('RM');
        let method = "purchase"; // Debit
        let action = "Sale";

        if (reg_no && medical_record_no) {
            let billing_list = [
                {
                    "billing_no": billing_no,
                    "billing_amount": total_amount
                }
            ];
            $.ajax({
                type: 'POST',
                url: 'http://192.167.4.250/si_kris/apm/api/medinfras/ecrlink/sale',
                // url: 'http://127.0.0.1:8000/api/apm/ecrlink/sale',
                data: JSON.stringify ({
                    medical_record_no: medical_record_no,
                    registration_no: reg_no,
                    billing_list: billing_list,
                    total_amount: total_amount,
                    method: method,
                    action: action
                }),
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Data terkirim untuk pembayaran debit!');                   
                    console.log('medical_record_no: ', medical_record_no);                   
                    console.log('registration_no: ', reg_no);
                    console.log('billing_list: ', billing_list);                   
                    console.log('total_amount: ', total_amount);
                    console.log('method: ', method);
                    console.log('action: ', action);      

                    if (response) {             
                        let tot_amount = parseInt(total_amount);
                        let textMessage = `Total tagihan pasien: Rp ${tot_amount.toLocaleString()}`;

                        $('#total_tagihan').text(textMessage);

                        console.log('response: ', response);
                    } else {
                        console.log('Ada masalah tidak terduga');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Status:', status);
                    console.log('Error:', error);
                    console.log('Response:', xhr.responseText);
                }
            });
        } else {
            console.log('Data tidak ditemukan!');
        }
    }
    
    cardPayment();
});