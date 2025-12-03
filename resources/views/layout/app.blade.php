<<<<<<< HEAD
{{--
=======
{{-- 
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
    Master Layout Template
    Doel: Hoofdlayout voor alle paginas met navigatie, footer en modals
    Features:
        - Responsive navbar met offcanvas op mobiel
        - Rol-gebaseerde navigatie (Praktijkmanagement, Tandarts, Mondhygiënist, Assistent)
        - Success/error modals voor gebruikersfeedback
        - Bootstrap 5.3.3 styling
--}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
<<<<<<< HEAD
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'smilemore') }}</title>

=======

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SmilePro</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap 5.3.3 via Vite (app.css & app.js) -->
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-vh-100 d-flex flex-column">

    {{-- NAVBAR --}}
    <nav class="navbar navbar-dark bg-dark sticky-top navbar-expand-lg">
        <div class="container">
<<<<<<< HEAD
            <a class="navbar-brand" href="{{ route('home') }}">SmilePro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('afspraken.index') }}">
                                <i class="bi bi-calendar-check me-1"></i>Afspraken
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link">Uitloggen</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Inloggen</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Registreren</a>
                        </li>
                    @endauth
                </ul>
=======
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
                SmilePro
            </a>

            {{-- Toggler shows < lg; at lg+ the offcanvas turns into inline content --}}
            <button class="navbar-toggler"
                type="button" data-bs-toggle="offcanvas" data-bs-target="#mainNavCanvas" aria-controls="mainNavCanvas"
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
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                        @auth
                            {{-- Show role-based dashboard links --}}
                            @if(auth()->user()->hasRole('Praktijkmanagement'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-1"></i>Management Dashboard
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('management.medewerkers.index') }}">
                                            <i class="bi bi-people-fill me-1"></i>Medewerkers
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
                            
                            {{-- User dropdown --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                            {{-- Guest links --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Inloggen</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Registreren</a>
                            </li>
                        @endauth
                    </ul>
                </div>
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
<<<<<<< HEAD
    <main class="flex-grow-1">
=======
    <main class="flex-fill">
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
        @yield('content')
    </main>

    {{-- FOOTER --}}
<<<<<<< HEAD
    <footer class="py-4 border-top bg-body">
        <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-center gap-2">
            <small>© {{ date('Y') }} SmilePro - tandheelkunde pratijk</small>
            <div class="d-flex gap-3">
                <a href="{{ url('/info') }}">Praktische info</a>
                <a href="{{ url('/contact') }}">Contact</a>
                <a href="{{ url('/privacy') }}">Privacy</a>
            </div>
        </div>
    </footer>


    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-check-circle-fill me-2"></i>Gelukt!</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                    <h4 class="mt-3" id="successMessage">{{ session('success') }}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
=======
    <footer class="bg-dark text-light py-3 mt-auto">
        <div class="container d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="small">&copy; {{ date('Y') }} SmilePro.</span>
            <span class="small text-secondary">Project PRI2 · SmilePro praktijkbeheer</span>
        </div>
    </footer>

    {{-- GLOBAL SUCCESS MODAL --}}
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-success">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Succes</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="successModalMessage" class="mb-0">
                        {{-- Wordt dynamisch gevuld via JS --}}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Oké</button>
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
                </div>
            </div>
        </div>
    </div>

<<<<<<< HEAD
    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Fout</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-x-circle text-danger" style="font-size: 4rem;"></i>
                    <h4 class="mt-3" id="errorMessage">{{ session('error') }}</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
=======
    {{-- GLOBAL ERROR MODAL --}}
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Fout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="errorModalMessage" class="mb-0">
                        {{-- Wordt dynamisch gevuld via JS --}}
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Sluiten</button>
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
                </div>
            </div>
        </div>
    </div>

<<<<<<< HEAD
    {{-- Bootstrap JS Bundle (includes Popper) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Page-specific scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show success modal if there's a success message
            @if(session('success'))
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

=======
</body>
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
</html>