<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My App')</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="styles/style.css">
    @vite(['resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="navbar navbar-expand-lg navbar-light mb-4 p-0">
        <div class="container-fluid" id="navbar">
            <!-- <img src="images/logo_rs_panjang.jpg" alt="logo_rs" class="logo_img">
            <span class="navbar-brand">Program APM</span> -->
            <!-- <div>Some content will displayed here</div> -->
            <div id="carouselInterval" class="carousel slide" data-bs-ride="carousel">
                <!-- Indikator -->
                <div class="carousel-indicators justify-content-center align-items-center">
                    <button type="button" data-bs-target="#carouselInterval" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselInterval" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselInterval" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    <button type="button" data-bs-target="#carouselInterval" data-bs-slide-to="3" aria-label="Slide 4"></button>
                </div>

                <!-- Isi Carousel -->
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="2000">
                        <img src="images/abyan-athif-BCx6t5pJwVw-unsplash.jpg" class="d-block w-100" alt="item1">
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                        <img src="images/anders-jilden-AkUR27wtaxs-unsplash.jpg" class="d-block w-100" alt="item2">
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                        <img src="images/brittney-weng-P-BPGW56GFo-unsplash.jpg" class="d-block w-100" alt="item3">
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                        <img src="images/camille-brodard-9j1r-lLPW1o-unsplash.jpg" class="d-block w-100" alt="item4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid" id="content">
        <!-- <div>
            <a href="/apm/" onclick="sessionStorage.clear()"><i class="fa-solid fa-house"> Halaman utama</i></a>
        </div> -->
        @yield('content')
    </div>

    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span class="text-muted">© 2025 IT RS Dr. Oen Solo Baru</span>
        </div>
    </footer>

    <script src="bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="scripts/patientInfoScript.js"></script>
    @stack('scripts')
</body>
</html>
