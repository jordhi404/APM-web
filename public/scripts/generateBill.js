$(document).ready(function() {
    function generateBill() {
        console.log('generateBill function called!');
        let reg_no = sessionStorage.getItem('registrationNo');
        let selectedTransactions = sessionStorage.getItem('selected_transactions');
        let total_amount = sessionStorage.getItem('total_amount');
        let existedBillNo = sessionStorage.getItem('DB_bill_no');

        if (reg_no && selectedTransactions) {
            let transactions = JSON.parse(selectedTransactions);
            let detailList = '';

            if (existedBillNo === null || existedBillNo === 'null') {
                if(Array.isArray(transactions)) {
                    if(transactions.length === 1) {
                        detailList = transactions[0];
                    } else if(transactions.length > 1) {
                        detailList = transactions.join(',');
                    }
                } else {
                    detailList = transactions;
                }

                const payload = {                  
                        registrationNo: reg_no,
                        detailList: detailList
                }

                $.ajax({
                    type: 'POST',
                    url: 'http://192.167.4.250/apm-backend/api/apm/medinfras/generate-payment-bill',
                    data: JSON.stringify(payload),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log('Data berhasil dikirim ke Kris!')
                        console.log('registrationNo: ', reg_no);
                        console.log('detailList: ', detailList);
                        console.log('total_amount: ', total_amount);
                        console.log('Response: ', response);
                        console.log('Response message:', response.message);

                        let billNo = response.data.PatientBillingNo;

                        sessionStorage.setItem('billing_no', billNo);
                        sessionStorage.removeItem('DB_bill_no');
                    },
                    error: function(xhr, status, error) {
                        console.log('Status:', status);
                        console.log('Error:', error);
                        console.log('Response:', xhr.responseText);
                    }
                });
            } else {
                let billNo = existedBillNo;
                console.log('Menggunakan nomor tagihan yang sudah ada:', billNo);
                sessionStorage.setItem('billing_no', billNo);
                sessionStorage.removeItem('DB_bill_no');
            }
        } else {
            console.log('Data reg_no dan/atau transactionList tidak ditemukan di sessionStorage!');
        }
    }
    
    generateBill();
});