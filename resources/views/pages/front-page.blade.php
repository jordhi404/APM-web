@extends('layouts.templates')

@section('title', 'Welcome Page')

@push('styles')
    <style>
        .content-container {
            justify-content: center;
            align-items: center;
            display: flex;
            flex-direction: column;
        }

        #ads-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .ads.fade-out {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .slider {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .ads {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .ads.active {
            opacity: 1;
            z-index: 1;
        }

        .btn-front-page {
            height: 20vh;
            width: 35vw;
        }

        #front-btn-img {
            height: 20vh;
            width: 34vw;
        }
    </style>
@endpush

@section('content')
    <div class="content-container mt-4" id="content-main">
        <h3 style="margin-bottom: 10vh;"><strong>SELAMAT DATANG DI ANJUNGAN SELF PAYMENT RS DR.OEN SOLO BARU</strong></h3>
        <div class="sub-title" style="text-align: center; margin-bottom: 1vh;">
            <h4>SILAHKAN PILIH MENU DI BAWAH INI</h4>
        </div>
        <div class="d-flex gap-5 d-md-flex justify-content-md-end">
            <a href="" class="btn btn-front-page" id="lihat_layanan">
                <!-- <div class="mt-5">
                    <i class="fa-solid fa-magnifying-glass"></i><br>
                    LIHAT LAYANAN
                </div> -->
                <img src="images/Lihat promo.png" alt="lihat-promo" id="front-btn-img">
            </a>
            <a href="{{ url('/index') }}" class="btn btn-front-page" id="bayar_tagihan">
                <!-- <div class="mt-5">
                    <i class="fa-regular fa-money-bill-1"></i><br>
                    BAYAR TAGIHAN
                </div> -->
                <img src="images/Bayar Tagihan.png" alt="bayar-tagihan" id="front-btn-img">
            </a>
        </div>
    </div>

    <!-- Ads Video -->
    <!-- <div id="ads-container">
        <video id="ads-video" autoplay muted loop src="ads-video/MCU_Gizi.mp4" type="video/mp4"></video>
    </div> -->

    <!-- Ads -->
    <div id="ads-container">
        <div class="slider">
            <img src="images/Promo-depan-1.jpeg" class="ads active" />
            <img src="images/Promo-depan-2.jpeg" class="ads" />
        </div>
    </div>
@endsection

@push('scripts')
    @if (request()->routeIs('welcome'))
        <script>
            let idleTime = 0;
            const idleLimit = 30; // 30 seconds
            let idlePaused = false;

            function resetIdleTime() {
                console.log('[resetIdleTime] User activity detected, resetting idle time.');
                if (!idlePaused) {
                    idleTime = 0;
                }

                const ads = document.getElementById('ads-container');
                if (ads.style.display === 'flex') {
                    ads.style.display = 'none';
                    idlePaused = false;
                    console.log('[resetIdleTime] Iklan ditutup, redirect ke front page');
                    window.location.href = "{{ url('/') }}";
                }
            }

            /* Iklan video */
            // function showAdsVideo() {
            //     console.log('[showAdsVideo] Tidak ada aktivitas selama 15 detik, tampilkan iklan.');
            //     const ads = document.getElementById('ads-container');
            //     ads.style.display = 'block';
            //     const video = document.getElementById('ads-video');
            //     idlePaused = true; // Pause idle timer
            //     video.currentTime = 0; // Reset video to start
            //     video.play().then(() => {
            //         console.log('[showAdsVideo] Video iklan diputar.');
            //     }).catch((error) => {
            //         console.error('[showAdsVideo] Error memutar video iklan:', error);
            //     });
            // }

            /* Iklan gambar */
            function showAdsSlider() {
                console.log('[showAdsSlider] Tidak ada aktivitas, tampilkan slider iklan.');
                const ads = document.getElementById('ads-container');
                ads.style.display = 'flex';

                const slides = document.querySelectorAll('.ads');
                let current = 0;

                idlePaused = true;

                // Tampilkan slide pertama
                slides[current].classList.add('active');

                const slideDuration = 10000; // 10 detik per slide
                const transitionInterval = setInterval(() => {
                    slides[current].classList.remove('active');
                    current++;

                    if (current >= slides.length) {
                        clearInterval(transitionInterval);
                        ads.classList.add('fade-out');
                        // Setelah selesai kembali ke beranda
                        setTimeout(() => {
                            window.location.href = "{{ url('/') }}";
                        }, 1000); // delay sesuai durasi fade-out
                        return;
                    }

                    slides[current].classList.add('active');
                }, slideDuration);
            }

            document.addEventListener("DOMContentLoaded", function() {
                console.log('[DOMContentLoaded] Inisialisasi pengamat idle...');
                window.onkeypress = resetIdleTime;
                window.ontouchstart = resetIdleTime;
                window.onclick = resetIdleTime;

                setInterval(function() {
                    if (!idlePaused) {
                        idleTime++;
                        console.log(`[idleTimer] idleTime sekarang: ${idleTime}`);
                        if (idleTime >= idleLimit) {
                            showAdsSlider();
                        }
                    }
                }, 1000);
            });
        </script>
    @endif
    <script>
        $('#lihat_layanan').click(function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Fitur ini belum tersedia',
                text: 'Fitur ini akan tersedia di masa mendatang 🙇.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endpush
