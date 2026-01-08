@extends('layout.app')

{{--
    Medewerker Dashboard View
    Doel: Hoofdpagina voor medewerkers met toegang tot behandel- en factuurtools
    Toegang: Praktijkmanagement, Tandarts, Mondhygiënist, Assistent rollen
    Functies: Navigatie naar facturenbeheer en andere medewerker taken
--}}

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-briefcase me-2"></i>Medewerker Dashboard
        </h1>
        {{-- Dynamisch badge op basis van eerste rol --}}
        <span class="badge bg-info">
            @if(auth()->user()->hasRole('Praktijkmanagement'))
                Praktijkmanagement
            @elseif(auth()->user()->hasRole('Tandarts'))
                Tandarts
            @elseif(auth()->user()->hasRole('Mondhygiënist'))
                Mondhygiënist
            @elseif(auth()->user()->hasRole('Assistent'))
                Assistent
            @else
                Medewerker
            @endif
        </span>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-receipt-cutoff text-success fs-3"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Facturen beheren</h5>
                        </div>
                    </div>
                    <p class="card-text text-muted">Beheer alle facturen en betalingen</p>
                    <a href="{{ route('medewerker.factuur.index') }}" class="btn btn-outline-success btn-sm">Beheren</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-calendar-check text-primary fs-3"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Afspraken beheren</h5>
                        </div>
                    </div>
                    <p class="card-text text-muted">Bekijk en maak nieuwe afspraken</p>
                    <a href="{{ route('medewerker.afspraken.index') }}" class="btn btn-outline-primary btn-sm">Beheren</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
