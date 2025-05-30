@extends ('layouts.templates')

@section ('title', 'Payment Canceled')

@push ('styles')
    <style>
        .container {
            height: 69%;
        }

        .row {
            position: relative;
            top: 10%;
        }

        #payment-check {
            width: 27vw;
            height: 30vh;
        }
    </style>
@endpush

@section ('content')
    <div class="container" id="content-main">
        <div class="row d-flex d-column justify-content-center">
            <img src="images/cancel.png" alt="canceled" id="payment-check">
            <div class="col-md-12 mt-3">
                <h1 class="text-center">Transaksi Dibatalkan</h1>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
        <button class="btn btn-primary" id="back-btn">Kembali ke beranda</button>
    </div>
@endsection