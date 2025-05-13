@extends('layouts.templates')

@section('title', 'metode-bayar')

@push('styles')
    <style>      
        .option-card {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #e9f3ff;
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 26px;
            height: 240px;
            width: 200px;
        }

        .option-card:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            background-image: url('images/abyan-athif-BCx6t5pJwVw-unsplash.jpg');
            border-color: #007bff;
        }

        .option-card.selected {
            border-color: #007bff;
            background-image: url('images/abyan-athif-BCx6t5pJwVw-unsplash.jpg');
        }

        .option-icon {
            font-size: 30px;
            margin-bottom: 10px;
            display: block;
        }

        .hidden {
            display: none;
        }
    </style>
@endpush

@section ('content')
    <div class="payment-container mt-4"> 
        <div class="text-center mb-4" style="font-size: 25px;">Pilih Metode Bayar</div><br>
        
        <div class="d-grid gap-5 d-flex justify-content-start">
            <div class="col-md-6">
                <div class="option-card" data-method="Qris">
                    <i class="fa-solid fa-qrcode option-icon"></i>
                    QRIS
                </div>
            </div>
            <div class="col-md-6">
                <div class="option-card" data-method="Transfer">
                    <i class="fa-solid fa-money-bill-transfer option-icon"></i>
                    Transfer
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12 text-center">
                <button class="btn btn-primary hidden" id="btn-lanjut">Lanjut</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const optionCards = document.querySelectorAll('.option-card');
        const btnLanjut = document.getElementById('btn-lanjut');
        let selectedMethod = null;

        // Saat card diklik
        optionCards.forEach(card => {
            card.addEventListener('click', () => {
                const method = card.dataset.method;

                // Jika selected diklik lagi -> unselect
                if (card.classList.contains('selected')) {
                    card.classList.remove('selected');
                    selectedMethod = null;
                    btnLanjut.classList.add('hidden');
                } else {
                    // Hapus class selected dari semua card
                    optionCards.forEach(c => c.classList.remove('selected'));

                    // Tambahkan class selected ke card yang diklik
                    card.classList.add('selected');
                    selectedMethod = method;
                    btnLanjut.classList.remove('hidden');
                }
            });
        });

        // Saat tombol lanjut diklik
        btnLanjut.addEventListener('click', () => {
            let url = '';

            if (selectedMethod === 'Qris') {
                // url = `/apm/qr-payment`;
                url = '/qr-payment';
            } else if (selectedMethod === 'Transfer') {
                // url = '/apm/tf-payment';
                url = '/tf-payment';
            }

            if (url !== '') {
                window.location.href = url;
            } else {
                alert('Silakan pilih metode pembayaran.');
            }
        });
    </script>
    <script src="scripts/generateBill.js"></script>
@endpush