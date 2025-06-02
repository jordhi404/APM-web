@extends('layouts.templates')

@section('title', 'details')

@section ('content')
    <div class="container-fluid mt-4" id="details-content-main">
        <div class="row mb-1">
            <div class="col-md-6">
                <h3>Rincian Biaya</h3>
                <p id="info-kunjungan"></p>
            </div>
            <div class="col-md-6 text-md-end">
                <div id="patient-info" class="mb-3"></div>
            </div>
        </div>
        <div class="accordion" id="accordionTagihan">
            <!-- Diolah di patientBillScript.js -->
        </div>
        <div class="row mt-3 mb-1">
            <div class="col-6">
                <h6 class="text-right mt-6" id="total-tagihan"></h6>
            </div>
            <div class="col-6 text-end">
                <div>
                    <a class="btn btn-primary" id="btnBayar" href="{{ route('metode-bayar', ['RegistrationNo' => session('registration_no')]) }}">Bayar</a>
                    <button class="btn btn-danger ms-3" id="back-btn">Batal</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#btnBayar', function(e) {
            e.preventDefault();

            let url = $(this).attr('href');

            Swal.fire({
                title: 'Lanjutkan Pembayaran?',
                text: "Harap pastikan rincian tagihan anda sudah benar.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Tunggu dulu',
                reverseButtons: true
            }).then((result) => {
                if(result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    </script>
    <script src="scripts/patientBillScript.js"></script>
@endpush