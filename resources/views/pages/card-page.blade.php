@extends ('layouts.templates')

@section ('title', 'Cash Page')

@push('styles')
    <style>
        #swipe-card {
            width: 300px; /* Atur lebar gambar sesuai kebutuhan */
            height: auto; /* Biarkan tinggi otomatis untuk menjaga proporsi */
        }
    </style>
@endpush

@section ('content')
    <div class="payment-container mt-4 flex-column justify-content-center" id="content-main">
        <div class="row mb-3">
            <div class="text-center" style="font-size: 25px;">Gesekkan kartu pada alat yang tersedia</div>
            <!-- <div class="col-md-6 text-md-end">
                <div id="patient-info" class="mb-3"></div>
            </div> -->
        </div>
        <img src="images/swipe-card.png" alt="swipe-card" id="swipe-card" class="img-fluid mb-3">
    </div>

    <div>
        <a href="{{ url('/payment-success') }}" class="btn btn-primary">Button</a>
    </div>
@endsection