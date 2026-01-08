@extends('layout.app')

@section('content')
@php
    $isAdmin = request()->routeIs('admin.*');
    $afsprakenPrefix = $isAdmin ? 'admin.afspraken' : 'medewerker.afspraken';
    $indexRoute = $afsprakenPrefix . '.index';
    $updateRoute = $afsprakenPrefix . '.update';
    $destroyRoute = $afsprakenPrefix . '.destroy';
@endphp

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">
            <i class="bi bi-pencil-square me-2"></i>Afspraak Bewerken
        </h1>
        <a href="{{ route($indexRoute) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Terug
        </a>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route($updateRoute, $afspraak->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Patiënt --}}
                    <div class="col-md-6">
                        <label for="patientid" class="form-label">Patiënt <span class="text-danger">*</span></label>
                        <select name="patientid" id="patientid" class="form-select @error('patientid') is-invalid @enderror" required>
                            <option value="">-- Selecteer patiënt --</option>
                            @foreach($patienten as $patient)
                            <option value="{{ $patient->id }}" {{ old('patientid', $afspraak->patientid) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->naam }} ({{ $patient->nummer }})
                            </option>
                            @endforeach
                        </select>
                        @error('patientid')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Medewerker --}}
                    <div class="col-md-6">
                        <label for="medewerkerid" class="form-label">Medewerker <span class="text-danger">*</span></label>
                        <select name="medewerkerid" id="medewerkerid" class="form-select @error('medewerkerid') is-invalid @enderror" required>
                            <option value="">-- Selecteer medewerker --</option>
                            @foreach($medewerkers as $medewerker)
                            <option value="{{ $medewerker->id }}" {{ old('medewerkerid', $afspraak->medewerkerid) == $medewerker->id ? 'selected' : '' }}>
                                {{ $medewerker->naam }} ({{ $medewerker->medewerkertype }})
                            </option>
                            @endforeach
                        </select>
                        @error('medewerkerid')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Datum --}}
                    <div class="col-md-4">
                        <label for="datum" class="form-label">Datum <span class="text-danger">*</span></label>
                        <input type="date" name="datum" id="datum" 
                               class="form-control @error('datum') is-invalid @enderror" 
                               value="{{ old('datum', $afspraak->datum) }}" required>
                        @error('datum')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tijd --}}
                    <div class="col-md-4">
                        <label for="tijd" class="form-label">Tijd <span class="text-danger">*</span></label>
                        <input type="time" name="tijd" id="tijd" 
                               class="form-control @error('tijd') is-invalid @enderror" 
                               value="{{ old('tijd', \Carbon\Carbon::parse($afspraak->tijd)->format('H:i')) }}" required>
                        @error('tijd')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="Bevestigd" {{ old('status', $afspraak->status) == 'Bevestigd' ? 'selected' : '' }}>Bevestigd</option>
                            <option value="Geannuleerd" {{ old('status', $afspraak->status) == 'Geannuleerd' ? 'selected' : '' }}>Geannuleerd</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Opmerking --}}
                    <div class="col-12">
                        <label for="opmerking" class="form-label">Opmerking</label>
                        <textarea name="opmerking" id="opmerking" rows="3" 
                                  class="form-control @error('opmerking') is-invalid @enderror" 
                                  maxlength="500">{{ old('opmerking', $afspraak->opmerking) }}</textarea>
                        @error('opmerking')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="bi bi-trash me-1"></i>Verwijderen
                    </button>
                    <div>
                        <a href="{{ route($indexRoute) }}" class="btn btn-outline-secondary me-2">Annuleren</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>Opslaan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Afspraak Verwijderen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Weet je zeker dat je deze afspraak wilt verwijderen?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <form action="{{ route($destroyRoute, $afspraak->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Verwijderen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
