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
    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
        <button class="btn btn-primary" id="back-btn">Kembali ke beranda</button>
        <button class="btn btn-success" id="cetak-nota"><i class="fa-solid fa-printer"></i>Cetak Nota</button>
    </div>

    <div id="nota-container" style="display:none; font-family: monospace;">
        <h3>RS Contoh Sehat</h3>
        <p>Jl. Kesehatan No.10, Telp: 021-12345678</p>
        <hr>
        <p>Registration No: <span id="reg-no"></span></p>
        <p>Nama Pasien: <span id="nama-pasien"></span></p>
        <hr>
        <table id="rincian-nota" border="0" width="100%"></table>
        <hr>
        <p>Total: <strong><span id="total-tagihan"></span></strong></p>
        <p>ID Pembayaran: <span id="payment-id"></span></p>
    </div>
@endsection

@push ('scripts')
    <script src="scripts/generatePaymentID.js"></script>
    <script src="scripts/print-nota.js"></script>
@endpush