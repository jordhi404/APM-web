@extends ('layouts.templates')

@section ('title', 'TF Page')

@push('styles')
    <style>
        #transfer-gif {
            width: 300px; /* Atur lebar gambar sesuai kebutuhan */
            height: auto; /* Biarkan tinggi otomatis untuk menjaga proporsi */
        }
    </style>
@endpush

@section ('content')
    <div class="payment-container mt-4 flex-column justify-content-center" id="content-main">
        <div class="row mb-3">
            <div class="text-center" style="font-size: 25px;">Silahkan transfer ke rekening berikut</div>
            <!-- <div class="col-md-6 text-md-end">
                <div id="patient-info" class="mb-3"></div>
            </div> -->
        </div>
        <img src="images/online-payment.gif" alt="transfer-gif" id="transfer-gif" class="img-fluid mb-3">
        <div class="row mb-3">
            <div class="text-center" style="font-size: 20px;">Bank X</div>
            <div class="text-center" style="font-size: 20px;"><strong>No. Rekening: 1234567890</strong></div>
            <div class="text-center" style="font-size: 20px;">A/N: RS Dr. Oen Solo Baru</div>
        </div>
    </div>

    <div>
        <button class="btn btn-primary" onclick="window.location.href = '/payment-success'">Button</button>
    </div>
@endsection