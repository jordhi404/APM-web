@extends('layouts.templates')

@section ('title', 'Welcome Page')

@push('styles')
    <style>
        .content-container {
            justify-content: center;
            align-items: center;
            display: flex;
            flex-direction: column;
        }
    </style>
@endpush

@section ('content')
    <div class="content-container mt-4">
        <h3>Selamat Datang di Sistem Pembayaran Mandiri RS Dr. Oen Solo Baru</h3>
        <button class="btn btn-primary" onclick="window.location.href = '/index'">Mulai Pembayaran</button>
    </div>
@endsection