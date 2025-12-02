@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h2 fw-bold mb-4">
                <i class="bi bi-person-circle me-2"></i>Mijn Profiel
            </h1>

            {{-- Profile Information Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Profielinformatie</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $user->name) }}" 
                                required 
                                class="form-control @error('name') is-invalid @enderror"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mailadres</label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                value="{{ old('email', $user->email) }}" 
                                required 
                                class="form-control @error('email') is-invalid @enderror"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rollen</label>
                            <div>
                                @forelse($user->roles as $role)
                                    <span class="badge bg-primary me-1">{{ ucfirst($role->name) }}</span>
                                @empty
                                    <span class="text-muted">Geen rollen toegewezen</span>
                                @endforelse
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lid sinds</label>
                            <p class="text-muted">{{ $user->created_at->format('d-m-Y') }}</p>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Opslaan
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                Annuleren
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Password Change Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Wachtwoord wijzigen</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Huidig wachtwoord</label>
                            <input 
                                type="password" 
                                name="current_password" 
                                id="current_password" 
                                required 
                                class="form-control @error('current_password') is-invalid @enderror"
                            >
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nieuw wachtwoord</label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                required 
                                class="form-control @error('password') is-invalid @enderror"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Minimaal 8 karakters</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Bevestig nieuw wachtwoord</label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="password_confirmation" 
                                required 
                                class="form-control"
                            >
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-key me-1"></i>Wachtwoord wijzigen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
