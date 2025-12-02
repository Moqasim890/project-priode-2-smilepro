{{--
    Registratie View
    Doel: Registratieformulier voor nieuwe patiënten
    Toegang: Alleen gasten (niet-ingelogde gebruikers)
    Features: Account aanmaken, persoonsgegevens, automatische Patiënt rol toewijzing
    Note: Nieuwe gebruikers krijgen automatisch de "Patiënt" rol via User::booted() event
--}}
@extends('layout.app')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h4 fw-bold text-center mb-4">Registreren</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger small">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam (voor account)</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus class="form-control @error('name') is-invalid @enderror">
                            <small class="text-muted">Gebruikersnaam voor login</small>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-5">
                                <label for="voornaam" class="form-label">Voornaam</label>
                                <input type="text" name="voornaam" id="voornaam" value="{{ old('voornaam') }}" required class="form-control @error('voornaam') is-invalid @enderror">
                            </div>
                            <div class="col-md-3">
                                <label for="tussenvoegsel" class="form-label">Tussenvoegsel</label>
                                <input type="text" name="tussenvoegsel" id="tussenvoegsel" value="{{ old('tussenvoegsel') }}" class="form-control @error('tussenvoegsel') is-invalid @enderror">
                            </div>
                            <div class="col-md-4">
                                <label for="achternaam" class="form-label">Achternaam</label>
                                <input type="text" name="achternaam" id="achternaam" value="{{ old('achternaam') }}" required class="form-control @error('achternaam') is-invalid @enderror">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="geboortedatum" class="form-label">Geboortedatum</label>
                            <input type="date" name="geboortedatum" id="geboortedatum" value="{{ old('geboortedatum') }}" required class="form-control @error('geboortedatum') is-invalid @enderror">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mailadres</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Wachtwoord</label>
                            <input type="password" name="password" id="password" required class="form-control @error('password') is-invalid @enderror">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Bevestig wachtwoord</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus-fill me-1"></i>Registreren</button>
                            <a href="{{ route('login') }}" class="small">Al een account?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
