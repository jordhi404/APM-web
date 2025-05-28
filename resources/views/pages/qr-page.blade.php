@extends ('layouts.templates')

@section ('title', 'QR Code Page')

@push('styles')
    <style>
        #total_tagihan {
            font-size: 25px;
            font-weight: bold;
        }

        #btn-batal {
            padding: 0;
            width: 20vw;
            height: 5vh;
            font-size: 27px;
            font-weight: bold;
        }
    </style>
@endpush

@section ('content')
    <div class="payment-container mt-4 flex-column" id="content-main">
        <div class="row mb-3">
            <div class="text-center" style="font-size: 25px;">Silahkan scan QR Code berikut</div>
            <!-- <div class="col-md-6 text-md-end">
                <div id="patient-info" class="mb-3"></div>
            </div> -->
        </div>
        <!-- <img src="images/rick-roll-qr.png" alt="QR Code" id="qr-code" class="img-fluid mb-3"> -->
        <div class="mb-1" id="qrcode" style="display: none;"></div>
        <div class="text-center" id="total_tagihan"></div>
    </div>

    <div class="d-flex justify-content-center align-items-center">
        <button class="btn btn-danger" id="btn-batal">Batal</button>
    </div>
    
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="scripts/generateQR.js"></script>
    @vite('resources/js/qr-payment.js')
    <script>
        $('#btn-batal').click(function() {
            Swal.fire({
                title: 'Batalkan Pembayaran?',
                text: "Apakah anda yakin akan membatalkan pembayaran untuk transaksi ini?",
                icon: 'warning',
                allowedOutsideClick: true,
                confirmButtonText: 'Ya',
            }).then((result) => {
                if(result.isConfirmed) {
                    window.location.href = '/payment-canceled';
                    // window.location.href = '/apm/payment-canceled';
                }
            });
        });
    </script>
@endpush