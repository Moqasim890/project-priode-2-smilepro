<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'smilemore') }}</title>

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-vh-100 d-flex flex-column">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-dark bg-dark sticky-top navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
                <i class="bi bi-calendar2-heart"></i> SmilePro
            </a>

            <button class="navbar-toggler"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#mainNavCanvas"
                aria-controls="mainNavCanvas"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Offcanvas on small screens; inline at lg+ thanks to .offcanvas-lg --}}
            <div class="offcanvas offcanvas-end offcanvas-lg text-bg-dark" tabindex="-1" id="mainNavCanvas"
                aria-labelledby="mainNavCanvasLabel" data-bs-scroll="true">
                <div class="offcanvas-header d-lg-none">
                    <h5 class="offcanvas-title m-0" id="mainNavCanvasLabel">Menu</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('afspraken.index') }}">
                                <i class="bi bi-calendar-check"></i> Afspraken
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/patienten') }}">
                                <i class="bi bi-people"></i> Patiënten
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/medewerkers') }}">
                                <i class="bi bi-person-badge"></i> Medewerkers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/facturen') }}">
                                <i class="bi bi-receipt"></i> Facturen
                            </a>
                        </li>
                    </ul>
                </div>
            </div> {{-- /offcanvas --}}
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow-1 py-3">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="py-4 border-top bg-body">
        <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-center gap-2">
            <small>© {{ date('Y') }} SmilePro</small>
            <div class="d-flex gap-3">
                <a href="{{ url('/info') }}">Praktische info</a>
                <a href="{{ url('/contact') }}">Contact</a>
                <a href="{{ url('/privacy') }}">Privacy</a>
            </div>
        </div>
    </footer>

    {{-- Bootstrap JS Bundle (includes Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Flash modals --}}
    @if(session('success') || session('error'))
    <div class="modal fade" id="flashModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                @if(session('success'))
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle-fill me-2"></i>Gelukt!</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">{{ session('success') }}</h4>
                </div>
                @endif

                @if(session('error'))
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Fout</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-x-circle text-danger" style="font-size: 4rem;"></i>
                    <h4 class="mt-3">{{ session('error') }}</h4>
                </div>
                @endif

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const m = document.getElementById('flashModal');
            if (m) new bootstrap.Modal(m).show();
        });
    </script>
    @endif
</body>
</html>