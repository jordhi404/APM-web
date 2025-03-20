@extends('layouts.templates')

@section ('title', 'Welcome Page')

@section ('content')
    <div class="card">
        <div class="card-body">
            <h3>Selamat Datang di Sistem Pembayaran Mandiri RS Dr. Oen Solo Baru</h3>
            <button class="btn btn-primary" onclick="window.location.href = '/index'">Mulai Pembayaran</button>
        </div>
    </div>
@endsection