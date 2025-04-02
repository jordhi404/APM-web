@extends('layouts.templates')

@section('title', 'index')

@section('content')
    <div class="container mt-4" id="content-main">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="input-RM">Masukkan No. Rekam Medis:</label>
                    <input type="text" class="form-control" id="input-RM" placeholder="Contoh: 00-11-22-33"></input><br>
                    <button class="btn btn-primary" id="btn-check">Cek Pasien</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="scripts/patientInfoScript.js"></script>
@endpush