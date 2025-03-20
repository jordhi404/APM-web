@extends('layouts.templates')

@section('title', 'details')

@section ('content')
    
        <h2>Rincian Biaya</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sewa Kamar</td>
                    <td>1</td>
                    <td>buah</td>
                    <td>300.000</td>
                </tr>
                <tr>
                    <td>Konsultasi Gizi</td>
                    <td>4</td>
                    <td>hari</td>
                    <td>80.000</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td>380.000</td>
                </tr>
            </tbody>
        </table>
        <button class="btn btn-primary" onclick="window.location.href = '/metode-bayar'">Lanjut Pembayaran</button>
    
@endsection