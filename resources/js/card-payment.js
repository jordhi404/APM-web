document.addEventListener('DOMContentLoaded', function () {
    if (!window.Echo) {
        console.error('Echo belum tersedia saat DOMContentLoaded card.payment');
        return;
    } else {
        console.log('Echo sudah tersedia untuk card.payment');
    }

    window.Echo.channel('card.payment.Y4Y').listen('.card.payment', (e) => {
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