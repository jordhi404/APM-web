@extends('layouts.templates')

@section('title', 'metode-bayar')

@push('styles')
<style>
        .radio-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px; /* Jarak antar pilihan */
        }

        .radio-option {
            display: flex;
            align-items: center;
            width: 200px; /* Samakan lebar semua kotak */
            padding: 10px;
            border: 2px solid black;
            border-radius: 10px;
            background-color: white;
        }

        .radio-option input {
            margin-right: 10px; /* Jarak antara radio button dan teks */
        }

        .radio-option:hover {
            background-color: #f0f0f0;
        }

        .radio-option input:checked {
            background-color:#ffffff;
            font-weight: bold;
        }
    </style>
@endpush

@section ('content')
    <div class="payment-container mt-4" id="content-main"> 
        <div class="text-center" style="font-size: 25px;">Pilih Metode Bayar</div><br>
        <div class="radio-container">
            <label class="radio-option">
                <input type="radio" name="payment-method" value="Qris" checked>
                <i class="fa-solid fa-qrcode" style="margin-right: 5px;"></i>
                <label>QRIS</label>
            </label>

            <label class="radio-option">
                <input type="radio" name="payment-method" value="Transfer">
                <i class="fa-solid fa-money-bill-transfer" style="margin-right: 5px;"></i>
                <label>Transfer</label>
            </label>

            <label class="radio-option">
                <input type="radio" name="payment-method" value="Cash">
                <i class="fa-solid fa-wallet" style="margin-right: 5px;"></i>
                <label>Cash</label>
            </label>
        </div> <br>
        <button class="btn btn-primary" onclick="window.location.href = '/payment-success'">Lanjut</button>
    </div>
@endsection