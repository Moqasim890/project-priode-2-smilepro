<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SmilePro Tandartspraktijk' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero{background:linear-gradient(135deg,#0d6efd,#37b1e3);color:#fff;padding:6rem 0}
        .service-card:hover{transform:translateY(-4px);transition:.3s}
        .bg-soft{background:#f8f9fa}
    </style>
</head>
<body class="min-vh-100 d-flex flex-column bg-soft">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}"><i class="bi bi-emoji-smile text-primary me-1"></i>SmilePro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#diensten">Diensten</a></li>
                    <li class="nav-item"><a class="nav-link" href="#team">Team</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @auth
                        <li class="nav-item me-3"><span class="text-secondary small">Hallo, {{ auth()->user()->name }}</span></li>
                        @if(auth()->user()->hasRole('admin'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a></li>
                        @endif
                        @if(auth()->user()->hasAnyRole(['admin','medewerker']))
                            <li class="nav-item"><a class="nav-link" href="{{ route('medewerker.dashboard') }}">Medewerker</a></li>
                        @endif
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-outline-danger btn-sm">Uitloggen</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Inloggen</a></li>
                        <li class="nav-item"><a href="{{ route('register') }}" class="btn btn-primary btn-sm">Registreren</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <main class="flex-grow-1">
        @yield('content')
    </main>
    <footer class="py-4 border-top bg-white mt-5">
        <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-center gap-2">
            <small class="text-muted">© {{ date('Y') }} SmilePro Tandartspraktijk. Alle rechten voorbehouden.</small>
            <div class="d-flex gap-3 small">
                <a href="#" class="text-decoration-none">Privacy</a>
                <a href="#" class="text-decoration-none">Voorwaarden</a>
                <a href="#contact" class="text-decoration-none">Contact</a>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>