@extends ('layouts.templates')

@section ('title', 'Cash Page')

@push('styles')
    <style>
        #cash-regist {
            width: 300px; /* Atur lebar gambar sesuai kebutuhan */
            height: auto; /* Biarkan tinggi otomatis untuk menjaga proporsi */
        }
    </style>
@endpush

@section ('content')
    <div class="payment-container mt-4 flex-column justify-content-center" id="content-main">
        <div class="row mb-3">
            <div class="text-center" style="font-size: 25px;">Silahkan menuju ke unit kasir terdekat</div>
            <!-- <div class="col-md-6 text-md-end">
                <div id="patient-info" class="mb-3"></div>
            </div> -->
        </div>
        <img src="images/cash-register.gif" alt="cash-regist" id="cash-regist" class="img-fluid mb-3">
    </div>

    <div>
        <button class="btn btn-primary" onclick="window.location.href = '/payment-success'">Button</button>
    </div>
@endsection