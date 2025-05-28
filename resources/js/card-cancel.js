document.addEventListener('DOMContentLoaded', function () {
    if (!window.Echo) {
        console.error('Echo belum tersedia saat DOMContentLoaded card.cancel');
        return;
    } else {
        console.log('Echo sudah tersedia untuk card.cancel');
    }

    window.Echo.channel('card.payment.cancel').listen('.card.cancel', (e) => {
        // window.location.href = "/payment-canceled"; // local side
        window.location.href = "/apm/payment-canceled"; // server side

        // const payload = e.data;

        // console.log('Payload:', payload);
    });
});