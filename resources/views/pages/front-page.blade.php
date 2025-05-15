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
        }
    </style>
@endpush

@section('content')
    <div class="content-container mt-4" id="content-main">
        <h3 style="margin-bottom: 10vh;"><strong>SELAMAT DATANG DI ANJUNGAN SELF PAYMENT RS DR.OEN SOLO BARU</strong></h3>
        <div class="sub-title" style="text-align: center; margin-bottom: 1vh;">
            <h4>SILAHKAN PILIH MENU DI BAWAH INI</h4>
        </div>
        <div class="d-grid gap-5 d-md-flex justify-content-md-end">
            <a href="" class="btn btn-primary" id="btn-front-page">
                <div class="mt-5">
                    <i class="fa-solid fa-magnifying-glass"></i><br>
                    LIHAT LAYANAN
                </div>
            </a>
            <a href="{{ url('/index') }}" class="btn btn-primary" id="btn-front-page">
                <div class="mt-5">
                    <i class="fa-regular fa-money-bill-1"></i><br>
                    BAYAR TAGIHAN
                </div>
            </a>
        </div>
    </div>

    <!-- Ads Video -->
    <div id="ads-container">
        <video id="ads-video" autoplay muted loop src="ads-video/MCU_Gizi.mp4" type="video/mp4"></video>
    </div>
@endsection

@push('scripts')
    @if (request()->routeIs('welcome'))
        <script>
            let idleTime = 0;
            const idleLimit = 15;
            let idlePaused = false;

            function resetIdleTime() {
                console.log('[resetIdleTime] User activity detected, resetting idle time.');
                if (!idlePaused) {
                    idleTime = 0;
                }

                const ads = document.getElementById('ads-container');
                if (ads.style.display === 'block') {
                    ads.style.display = 'none';
                    idlePaused = false;
                    console.log('[resetIdleTime] Iklan ditutup, redirect ke front page');
                    window.location.href = "{{ url('/') }}";
                }
            }

            function showAdsVideo() {
                console.log('[showAdsVideo] Tidak ada aktivitas selama 15 detik, tampilkan iklan.');
                const ads = document.getElementById('ads-container');
                ads.style.display = 'block';
                const video = document.getElementById('ads-video');
                idlePaused = true; // Pause idle timer
                video.currentTime = 0; // Reset video to start
                video.play().then(() => {
                    console.log('[showAdsVideo] Video iklan diputar.');
                }).catch((error) => {
                    console.error('[showAdsVideo] Error memutar video iklan:', error);
                });
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
                            showAdsVideo();
                        }
                    }
                }, 1000);
            });
        </script>
    @endif
@endpush
