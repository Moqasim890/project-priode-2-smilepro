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
                                        <i class="bi bi-speedometer2 me-1"></i>Management Dashboard
                                    </a>
                                </li>
                            @endif
                            @if(auth()->user()->hasAnyRole(['Praktijkmanagement', 'Tandarts', 'Mondhygiënist', 'Assistent']))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('medewerker.dashboard') }}">
                                        <i class="bi bi-briefcase me-1"></i>Medewerker Dashboard
                                    </a>
                                </li>
                            @endif
                            @if(auth()->user()->hasAnyRole(['Patiënt']))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('Patient.berichten.index') }}">
                                        <i class="bi bi-bell me-1"></i>{{ $aantalberichten }} Meldingen
                                    </a>
                                </li>
                            @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('medewerker.dashboard') }}">
                                <i class="bi bi-house me-1"></i>Dashboard
                            </a>
                        </li>

                        {{-- Afspraken --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('medewerker.afspraken.index') }}">
                                <i class="bi bi-calendar-check me-1"></i>Afspraken
                            </a>
                        </li>

                        {{-- Patiënten --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.patienten.index') }}">
                                <i class="bi bi-people me-1"></i>Patiënten
                            </a>
                        </li>

                        {{-- Facturen --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('medewerker.factuur.index') }}">
                                <i class="bi bi-receipt me-1"></i>Facturen
                            </a>
                        </li>

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
        @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-1"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
</body>
</html>
