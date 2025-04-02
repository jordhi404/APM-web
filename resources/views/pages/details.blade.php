@extends('layouts.templates')

@section('title', 'details')

@section ('content')
    <div class="container mt-4" id="content-main">
        <div class="row mb-3">
            <div class="col-md-6">
                <h3>Rincian Biaya</h3>
            </div>
            <div class="col-md-6 text-md-end">
                <div id="patient-info" class="mb-3"></div>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Banyak</th>
                    <th>Harga Satuan</th>
                    <th>Harga Akhir</th>
                </tr>
            </thead>
            <tbody>
                <!-- Diolah di function getPatientBill di script.js -->
            </tbody>
        </table>

        <h6 class="text-right mt-6" id="total-tagihan"></h6>

        <button class="btn btn-primary" onclick="window.location.href = '/metode-bayar'">Lanjut Pembayaran</button>
    </div>
@endsection

@push('scripts')
    <script src="scripts/patientBillScript.js"></script>
@endpush