@extends ('layouts.templates')

@section ('title', 'Payment Success')

@push ('styles')
    <style>
        .container {
            height: 70%;
        }

        .row {
            position: relative;
            top: 10%;
        }

        .btn {
            margin-left: 45%;
        }
    </style>
@endpush

@section ('content')
    <div class="container" id="content-main">
        <div class="row d-flex d-column justify-content-center">
            <img src="images/double-check.gif" alt="sucess" id="payment-check">
            <div class="col-md-12">
                <h1 class="text-center">Payment Success</h1>
                <p class="text-center">Thank you for your payment. Your transaction is successful.</p>
            </div>
        </div>
    </div>
    <button class="btn btn-primary" id="back-btn">Kembali ke beranda</button>
@endsection