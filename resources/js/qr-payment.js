document.addEventListener('DOMContentLoaded', function () {
    if (!window.Echo) {
        console.error('Echo belum tersedia saat DOMContentLoaded untuk qr-payment');
        return;
    } else {
        console.log('Echo sudah tersedia untuk qr-payment');
    }

    window.Echo.channel('paid.payment.5ucc355').listen('.paid.payment', (e) => {
        // console.log('Broadcast received:', e);
        // window.location.href = "/payment-success"; // local side
        window.location.href = "/apm/payment-success"; // server side

        const payload = e.data;

        // console.log('Payload:', payload);

        if (payload) {
            sessionStorage.setItem('remarks', payload.remarks || '');
            sessionStorage.setItem('referenceNo', payload.referenceNo || '');
            sessionStorage.setItem('issuerName', payload.issuerName || '');
        }
    });
});