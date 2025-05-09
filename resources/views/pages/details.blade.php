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
            <div class="col-md-6">
                <h6 class="text-right mt-6" id="total-tagihan"></h6>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-grid gap-3 d-md-flex justify-content-md-end">
                    <button class="btn btn-danger" id="back-btn">Batal</button>
                    <!-- <a class="btn btn-primary" href="{{ route('qr-payment', ['RegistrationNo' => session('registration_no')]) }}">Bayar</a> -->
                    <a class="btn btn-primary" id="btnBayar" href="{{ route('metode-bayar', ['RegistrationNo' => session('registration_no')]) }}">Bayar</a>
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
                text: "Pastikan total tagihan sudah benar. Anda tidak dapat kembali setelah melanjutkan.",
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