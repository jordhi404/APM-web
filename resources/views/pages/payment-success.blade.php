@extends ('layouts.templates')

@section ('title', 'Payment Success')

@push ('styles')
    <style>
        .container {
            height: 69%;
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
            <img src="images/double-check-bgless.gif" alt="sucess" id="payment-check">
            <div class="col-md-12">
                <h1 class="text-center">Pembayaran Berhasil</h1>
                <p class="text-center" style="font-size: 20px;">Pembayaran anda telah terverifikasi.</p><br> 
                <p class="text-center" style="font-size: 19px;">Terima kasih atas kunjungan anda 😊</p>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column gap-3 justify-content-center align-items-center">
        <p class="text-center" style="font-size: 20px;">
            Apakah anda ingin mencetak nota pembayaran?
        </p>
        <button class="btn btn-success" id="cetak-nota"><i class="fa-solid fa-printer"></i>Ya, cetak nota</button>
        <button class="btn btn-primary" id="back-btn">Tidak, kembali ke beranda</button>
    </div>

    <!-- <div id="nota-container" style="display:none; font-family: courrier new, monospace;">
        <div id="bill-title" class="text-center mb-3">
            <h3>RUMAH SAKIT DR. OEN SOLO BARU</h3>
            <P>Jalan Bahu Dlopo, Dusun II, Gedangan, Kec. Grogol, Kabupaten Sukoharjo, Jawa Tengah 57552</P>
            <P>Telp. (0271) 620220</P>
        </div>
        =================================================================
        <div id="transaction-detail">
            <p>Registration No: <span id="reg-no"></span></p>
            <p>Nama Pasien: <span id="nama-pasien"></span></p>
        </div>
        =================================================================
        <div id="bill-detail">
            <table id="rincian-nota" border="0" width="100%"></table>
        </div>
        =================================================================
        <div id="total-bill" class="mb-4">
            <p>Total: <strong><span id="total-tagihan"></span></strong></p>
        </div>

        <div id="promo-section" class="text-center" style="margin: 10px 0;">
            <p>Unduh Aplikasi RS OEN Solo Baru di Play Store</p>
            <img id="promo-qr" src="images/rick-roll-qr.png" alt="QR Aplikasi RS" style="width:100px; height:100px;">
        </div>
    </div> -->
@endsection

@push ('scripts')
    <script src="scripts/generatePaymentID.js"></script>
    <script src="scripts/print-nota.js"></script>
@endpush