@extends ('layouts.templates')

@section ('title', 'QR Code Page')

@section ('content')
    <div class="payment-container mt-4 flex-column" id="content-main">
        <div class="row mb-3">
            <div class="text-center" style="font-size: 25px;">Silahkan scan QR Code berikut</div>
            <!-- <div class="col-md-6 text-md-end">
                <div id="patient-info" class="mb-3"></div>
            </div> -->
        </div>
            <div id="qrcode"></div>
        <!-- <img src="images/rick-roll-qr.png" alt="QR Code" id="qr-code" class="img-fluid mb-3"> -->
        <!-- @if(isset($qrCode))
            <div class="visible-print text-center">
                {!! $qrCode !!}
            </div>
            <p class="mt-3">Pesan saat di-scan: <strong>{{ $message }}</strong></p>
        @else
            <p>QR Code belum tersedia.</p>
        @endif -->
    </div>

    <div>
        <button class="btn btn-primary" onclick="window.location.href = '/payment-success'">Button</button>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="scripts/generateQR.js"></script>
@endpush