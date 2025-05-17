@extends ('layouts.templates')

@section ('title', 'Print Nota Pembayaran')

@section ('content')
    <div class="container" id="content-main">
        <div class="row d-flex d-column justify-content-center">
            <div class="col-md-12">
                <h1 class="text-center">Mencetak Nota Pembayaran</h1>
                <p class="text-center">Nota ini hanya dicetak sekali</p>
            </div>
        </div>
    </div>
    <button class="btn btn-primary" id="back-btn">Kembali ke beranda</button>
@endsection