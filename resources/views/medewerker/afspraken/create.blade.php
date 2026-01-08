{{--
    Nieuwe Afspraak Aanmaken Pagina
    
    Doel: Formulier voor het aanmaken van een nieuwe afspraak
    Toegang: Praktijkmanagement, Tandarts, Mondhygiënist, Assistent rollen
    
    Data verwacht:
    - $patienten: Array van patiënt objecten voor dropdown
    - $medewerkers: Array van medewerker objecten voor dropdown
    
    Features:
    - Patiënt selectie dropdown
    - Medewerker selectie dropdown met type/specialisatie
    - Datum en tijd selectie
    - Status selectie
    - Validatie feedback

    =========================================
    SCENARIO'S
    =========================================
    
    Happy Scenario: Nieuwe afspraak succesvol toevoegen
    - Gebruiker komt van homepagina naar dashboard
    - Selecteert "Nieuwe afspraak toevoegen"
    - Voert geldige afspraakgegevens in
    - Slaat afspraak op
    - Afspraak wordt toegevoegd aan de agenda
    - Bevestiging wordt getoond dat afspraak succesvol is aangemaakt
    
    Unhappy Scenario: Tijdslot is bezet
    - Gebruiker kiest een tijdslot dat al bezet is
    - Probeert afspraak op te slaan
    - Foutmelding wordt getoond: "Dit tijdslot is niet beschikbaar"
    - Afspraak wordt NIET toegevoegd aan de agenda
    - Gebruiker moet ander tijdstip of medewerker kiezen
    =========================================
--}}

@extends('layout.app')

@section('title', 'Nieuwe Afspraak')

@section('content')
@php(
    $isAdmin = request()->routeIs('admin.*');
    $afsprakenPrefix = $isAdmin ? 'admin.afspraken' : 'medewerker.afspraken';
    $indexRoute = $afsprakenPrefix . '.index';
    $storeRoute = $afsprakenPrefix . '.store';
)

<div class="container py-4">
    {{-- Header bovenaan --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-calendar-plus me-2"></i>Nieuwe Afspraak
        </h1>
        <a href="{{ route($indexRoute) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Terug
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            {{-- Error melding --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Formulier Card --}}
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-clipboard-plus me-2"></i>Afspraak Gegevens</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route($storeRoute) }}" method="POST">
                        @csrf

                        {{-- Patiënt Selectie --}}
                        <div class="mb-3">
                            <label for="patientid" class="form-label">
                                <i class="bi bi-person me-1"></i>Patiënt <span class="text-danger">*</span>
                            </label>
                            <select name="patientid" id="patientid" 
                                    class="form-select @error('patientid') is-invalid @enderror" required>
                                <option value="">-- Selecteer Patiënt --</option>
                                @foreach($patienten as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patientid') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->naam }} ({{ $patient->nummer }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patientid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(count($patienten) == 0)
                                <div class="form-text text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Geen patiënten beschikbaar. Voeg eerst een patiënt toe.
                                </div>
                            @endif
                        </div>

                        {{-- Medewerker Selectie --}}
                        <div class="mb-3">
                            <label for="medewerkerid" class="form-label">
                                <i class="bi bi-person-badge me-1"></i>Medewerker <span class="text-danger">*</span>
                            </label>
                            <select name="medewerkerid" id="medewerkerid" 
                                    class="form-select @error('medewerkerid') is-invalid @enderror" required>
                                <option value="">-- Selecteer Medewerker --</option>
                                @foreach($medewerkers as $medewerker)
                                    <option value="{{ $medewerker->id }}" {{ old('medewerkerid') == $medewerker->id ? 'selected' : '' }}>
                                        {{ $medewerker->naam }} - {{ $medewerker->medewerkertype }}
                                        @if($medewerker->specialisatie)
                                            ({{ $medewerker->specialisatie }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('medewerkerid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if(count($medewerkers) == 0)
                                <div class="form-text text-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>
                                    Geen medewerkers beschikbaar. Voeg eerst een medewerker toe.
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        {{-- Datum en Tijd in een row --}}
                        <div class="row">
                            {{-- Datum --}}
                            <div class="col-md-6 mb-3">
                                <label for="datum" class="form-label">
                                    <i class="bi bi-calendar3 me-1"></i>Datum <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="datum" id="datum" 
                                       class="form-control @error('datum') is-invalid @enderror" 
                                       value="{{ old('datum', date('Y-m-d')) }}" 
                                       min="{{ date('Y-m-d') }}" required>
                                @error('datum')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tijd --}}
                            <div class="col-md-6 mb-3">
                                <label for="tijd" class="form-label">
                                    <i class="bi bi-clock me-1"></i>Tijd <span class="text-danger">*</span>
                                </label>
                                <input type="time" name="tijd" id="tijd" 
                                       class="form-control @error('tijd') is-invalid @enderror" 
                                       value="{{ old('tijd', '09:00') }}" required>
                                @error('tijd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Praktijk openingstijden: 08:00 - 17:00</div>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <label for="status" class="form-label">
                                <i class="bi bi-flag me-1"></i>Status <span class="text-danger">*</span>
                            </label>
                            <select name="status" id="status" 
                                    class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Bevestigd" {{ old('status', 'Bevestigd') == 'Bevestigd' ? 'selected' : '' }}>
                                    ✓ Bevestigd
                                </option>
                                <option value="Geannuleerd" {{ old('status') == 'Geannuleerd' ? 'selected' : '' }}>
                                    ✗ Geannuleerd
                                </option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Opmerking --}}
                        <div class="mb-4">
                            <label for="opmerking" class="form-label">
                                <i class="bi bi-chat-left-text me-1"></i>Opmerking
                            </label>
                            <textarea name="opmerking" id="opmerking" 
                                      class="form-control @error('opmerking') is-invalid @enderror" 
                                      rows="3" placeholder="Optionele opmerking...">{{ old('opmerking') }}</textarea>
                            @error('opmerking')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximaal 500 karakters</div>
                        </div>

                        <hr class="my-4">

                        {{-- Knoppen --}}
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('medewerker.afspraken.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-1"></i>Annuleren
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Afspraak Aanmaken
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Help tekst --}}
            <div class="card mt-4 border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="bi bi-info-circle me-1"></i>Informatie
                    </h6>
                    <ul class="mb-0 small text-muted">
                        <li>Velden met <span class="text-danger">*</span> zijn verplicht</li>
                        <li>Afspraken kunnen alleen voor vandaag of later worden gemaakt</li>
                        <li>Na het aanmaken kan de afspraak worden bewerkt of geannuleerd</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
