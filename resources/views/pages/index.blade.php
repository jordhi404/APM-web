@extends('layouts.templates')

@section('title', 'index')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="input-name">Masukkan No. Rekam Medis:</label>
                    <input type="text" class="form-control" id="input-RM" placeholder="Contoh: 00-11-22-33"></input><br>
                    <button class="btn btn-primary" id="btn-next" onclick="window.location.href = '/details'">Lanjut pembayaran</button>
                </div>
            </div>
        </div>
    </div>
@endsection