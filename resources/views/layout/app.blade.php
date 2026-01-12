<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SmilePro') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-vh-100 d-flex flex-column">
    <nav class="navbar navbar-dark bg-dark sticky-top navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                <i class="bi bi-emoji-smile me-1"></i>SmilePro
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                    @auth
                        {{-- Show role-based dashboard links --}}
                        @if(auth()->user()->hasRole('Praktijkmanagement'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.omzet.overzicht') }}">
                                    <i class="bi bi-cash-stack me-1"></i>Omzet
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('medewerker.dashboard') }}">
                                    <i class="bi bi-briefcase me-1"></i>Medewerker
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('medewerker.factuur.index') }}">
                                    <i class="bi bi-receipt me-1"></i>Facturen
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.patienten.index') }}">
                                    <i class="bi bi-people me-1"></i>Patiënten
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.users.index') }}">
                                    <i class="bi bi-person-gear me-1"></i>Gebruikers
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.berichten.index') }}">
                                    <i class="bi bi-envelope me-1"></i>Berichten
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.feedback.index') }}">
                                    <i class="bi bi-chat-square-text me-1"></i>Feedback
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->hasAnyRole(['Tandarts', 'Mondhygiënist', 'Assistent']))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('medewerker.dashboard') }}">
                                    <i class="bi bi-briefcase me-1"></i>Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('medewerker.afspraken.index') }}">
                                    <i class="bi bi-calendar-check me-1"></i>Afspraken
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('medewerker.factuur.index') }}">
                                    <i class="bi bi-receipt me-1"></i>Facturen
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->hasRole('Patiënt'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('Patient.berichten.index') }}">
                                    <i class="bi bi-bell me-1"></i>{{ $aantalberichten }} Meldingen
                                </a>
                            </li>
                        @endif

                        {{-- Divider --}}
                        <li class="nav-item d-none d-lg-block">
                            <span class="nav-link text-secondary">|</span>
                        </li>

                        {{-- User Dropdown --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person me-1"></i>Profiel
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-1"></i>Uitloggen
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Inloggen
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Registreren
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow-1">
        {{-- Flash Messages --}}
        @if(session('success') || session('ok'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white;">
                <i class="bi bi-check-circle-fill me-2" style="font-size: 1.2rem;"></i>
                <strong>{{ session('success') ?? session('ok') }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white;">
                <i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.2rem;"></i>
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('warning'))
        <div class="container mt-3">
            <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0" style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); color: white;">
                <i class="bi bi-exclamation-circle-fill me-2" style="font-size: 1.2rem;"></i>
                <strong>{{ session('warning') }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="container mt-3">
            <div class="alert alert-info alert-dismissible fade show shadow-sm border-0" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                <i class="bi bi-info-circle-fill me-2" style="font-size: 1.2rem;"></i>
                <strong>{{ session('info') }}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="py-4 border-top bg-light mt-auto">
        <div class="container text-center">
            <small class="text-muted">© {{ date('Y') }} SmilePro - Tandheelkunde Praktijk</small>
        </div>
    </footer>


    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                    <h5 class="modal-title"><i class="bi bi-check-circle-fill me-2"></i>Gelukt!</h5>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="mt-3 text-success fw-bold" id="successMessage">{{ session('success') ?? session('ok') }}</h4>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">
                        <i class="bi bi-check-lg me-1"></i>OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Fout</h5>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="mt-3 text-danger fw-bold" id="errorMessage">{{ session('error') }}</h4>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Sluiten
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Page-specific scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Show success modal if there's a success or ok message
            @if(session('success') || session('ok'))
                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            @endif

            // Show error modal if there's an error message
            @if(session('error'))
                const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            @endif
        });
    </script>
</body>
</html>
