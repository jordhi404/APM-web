<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My App')</title>
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/css/bootstrap.min.css">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <img src="images/logo_rs_panjang.jpg" alt="logo_rs" style="width: 16vw; height: 8vh;">
            <span class="navbar-brand">Program APM</span>
        </div>
    </nav>

    <div class="container-fluid" style="height: 78vh;">
        @yield('content')
    </div>

    <footer class="footer mt-auto py-3 bg-light">
        <div class="container">
            <span class="text-muted">© 2025 IT RS Dr. Oen Solo Baru</span>
        </div>
    </footer>

    <script src="bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
