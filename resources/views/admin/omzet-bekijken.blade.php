{{-- 
    =========================================
    OMZET BEKIJKEN VIEW
    =========================================
    
    Happy Scenario:
    - Praktijkmanager ziet omzetgegevens van de praktijk
    - Totale omzet wordt weergegeven
    - Overzicht per geselecteerde periode wordt getoond
    
    Unhappy Scenario:
    - Melding wordt getoond als er geen omzetgegevens zijn
    - Geen omzetoverzicht wordt weergegeven
    =========================================
--}}

@extends('layout.app')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-cash-coin me-2"></i>Omzet Bekijken
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Terug naar Dashboard
        </a>
    </div>

    {{-- =========================================
         UNHAPPY SCENARIO: Melding tonen
         Als er geen gegevens zijn voor de periode
         ========================================= --}}
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Periode Selectie Formulier --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-range me-2"></i>Selecteer Periode</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.omzet.bekijken') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_datum" class="form-label">Start Datum</label>
                    <input type="date" class="form-control" id="start_datum" name="start_datum" 
                           value="{{ $startDatum ?? date('Y-m-01') }}">
                </div>
                <div class="col-md-4">
                    <label for="eind_datum" class="form-label">Eind Datum</label>
                    <input type="date" class="form-control" id="eind_datum" name="eind_datum" 
                           value="{{ $eindDatum ?? date('Y-m-t') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Omzet Bekijken
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- =========================================
         HAPPY SCENARIO: Omzetgegevens tonen
         Wanneer er gegevens zijn voor de periode
         ========================================= --}}
    @if(!($geenGegevens ?? false))
        {{-- Totale Omzet Card --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Totale Omzet</h6>
                                <h2 class="mb-0 fw-bold">€{{ number_format($totaleOmzet ?? 0, 2, ',', '.') }}</h2>
                                <small class="text-white-50">
                                    Periode: {{ date('d-m-Y', strtotime($startDatum)) }} t/m {{ date('d-m-Y', strtotime($eindDatum)) }}
                                </small>
                            </div>
                            <i class="bi bi-currency-euro fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-1">Aantal Dagen met Omzet</h6>
                                <h2 class="mb-0 fw-bold">{{ count($omzetGegevens ?? []) }}</h2>
                                <small class="text-white-50">dagen binnen de periode</small>
                            </div>
                            <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Omzet Overzicht per Dag --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-table me-2"></i>Omzet Overzicht per Dag</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Datum</th>
                                <th class="text-center">Aantal Facturen</th>
                                <th class="text-end">Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($omzetGegevens ?? [] as $dag)
                            <tr>
                                <td>
                                    <i class="bi bi-calendar-event me-2 text-muted"></i>
                                    {{ date('d-m-Y', strtotime($dag->datum)) }}
                                    <small class="text-muted">({{ strftime('%A', strtotime($dag->datum)) }})</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $dag->aantal_facturen }}</span>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    €{{ number_format($dag->totaal_omzet, 2, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Geen omzetgegevens gevonden
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                        @if(count($omzetGegevens ?? []) > 0)
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td>Totaal</td>
                                <td class="text-center">
                                    {{ collect($omzetGegevens)->sum('aantal_facturen') }} facturen
                                </td>
                                <td class="text-end text-success fs-5">
                                    €{{ number_format($totaleOmzet ?? 0, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

    {{-- =========================================
         UNHAPPY SCENARIO: Geen gegevens beschikbaar
         Toon een duidelijke melding
         ========================================= --}}
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-file-earmark-x text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">Geen Omzetgegevens Beschikbaar</h4>
                <p class="text-muted mb-4">
                    Er zijn geen omzetgegevens gevonden voor de periode<br>
                    <strong>{{ date('d-m-Y', strtotime($startDatum)) }}</strong> t/m <strong>{{ date('d-m-Y', strtotime($eindDatum)) }}</strong>
                </p>
                <div class="d-flex justify-content-center gap-2">
                    <a href="{{ route('admin.omzet.bekijken') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Andere periode kiezen
                    </a>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                        <i class="bi bi-house me-1"></i>Terug naar Dashboard
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
