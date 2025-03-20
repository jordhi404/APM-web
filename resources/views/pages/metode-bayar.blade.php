@extends('layouts.templates')

@section('title', 'metode-bayar')

@section ('content')
    <div class="container"><div class="container" style="max-width: 400px; margin-top: 5vh; text-align: center; padding: 5px; border: 1px solid #ddd;"> 
        <p>Pilih Metode Pembayaran</p>
        <select class="form-select" id="payment-method" name="payment-method-list">
            <option>Qris</option>
            <option>Transfer</option>
            <option>Cash</option>
        </select>
        <br>
        <button class="btn btn-primary">Lanjut</button>
    </div>
@endsection