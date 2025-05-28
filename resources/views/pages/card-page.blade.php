@extends ('layouts.templates')

@section ('title', 'Cash Page')

@push('styles')
    <style>
        #swipe-card {
            width: 300px; /* Atur lebar gambar sesuai kebutuhan */
            height: auto; /* Biarkan tinggi otomatis untuk menjaga proporsi */
        }

        #total_tagihan {
            font-size: 25px;
            font-weight: bold;
        }
    </style>
@endpush

@section ('content')
    <div class="payment-container mt-4 flex-column justify-content-center" id="content-main">
        <div class="row mb-3">
            <div class="text-center" style="font-size: 25px;">Silahkan masukkan kartu debit pada mesin EDC dan ikuti alur pada mesin</div>
        </div>
        <img src="images/swipe-card.png" alt="swipe-card" id="swipe-card" class="img-fluid mb-1">
        <div class="text-center" id="total_tagihan"></div>
    </div>

    <!-- <div>
        <a href="{{ url('/payment-success') }}" class="btn btn-primary">Button</a>
    </div> -->
@endsection

@push ('scripts')
    <script src="scripts/card-paid.js"></script>
    @vite(['resources/js/card-payment.js', 'resources/js/card-cancel.js'])
@endpush