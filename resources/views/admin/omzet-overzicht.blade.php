@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-cash-stack me-2"></i>Omzet Overzicht
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Terug
        </a>
    </div>

    {{-- Datum Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.omzet.bekijken') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="start_datum" class="form-label">Van Datum</label>
                    <input type="date" id="start_datum" name="start_datum" class="form-control" 
                        value="{{ request('start_datum', date('Y-m-01')) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="eind_datum" class="form-label">Tot Datum</label>
                    <input type="date" id="eind_datum" name="eind_datum" class="form-control" 
                        value="{{ request('eind_datum', date('Y-m-t')) }}" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Filteren
                    </button>
                    <a href="{{ route('admin.omzet.bekijken') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Omzet Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white-50">Totale Omzet (Betaald)</h6>
                            <h2 class="mb-0">€{{ number_format($omzetTotaal ?? 0, 2, ',', '.') }}</h2>
                        </div>
                        <i class="bi bi-currency-euro fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        @foreach($factuurStats ?? [] as $stat)
        <div class="col-md-2">
            <div class="card {{ $stat->status == 'Betaald' ? 'bg-success' : ($stat->status == 'Onbetaald' ? 'bg-danger' : ($stat->status == 'Verzonden' ? 'bg-warning' : 'bg-secondary')) }} text-white">
                <div class="card-body text-center">
                    <h4 class="mb-0">{{ $stat->aantal }}</h4>
                    <small>{{ $stat->status }}</small>
                    <p class="mb-0 small">€{{ number_format($stat->totaalbedrag, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-3">
        {{-- Omzet per Maand --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Omzet per Maand</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Maand</th>
                                    <th class="text-center">Facturen</th>
                                    <th class="text-end">Bedrag</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($omzetPerMaand ?? [] as $maand)
                                <tr>
                                    <td>{{ $maand->maand_naam }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $maand->aantal_facturen }}</span>
                                    </td>
                                    <td class="text-end fw-bold">€{{ number_format($maand->totaal, 2, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Geen data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Openstaande Facturen --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle me-2"></i>Openstaande Facturen</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nummer</th>
                                    <th>Patiënt</th>
                                    <th>Datum</th>
                                    <th class="text-end">Bedrag</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($openstaandeFacturen ?? [] as $factuur)
                                <tr>
                                    <td><strong>{{ $factuur->nummer }}</strong></td>
                                    <td>{{ $factuur->patientnaam }}</td>
                                    <td>{{ \Carbon\Carbon::parse($factuur->datum)->format('d-m-Y') }}</td>
                                    <td class="text-end">€{{ number_format($factuur->bedrag, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $factuur->status == 'Onbetaald' ? 'danger' : 'warning' }}">
                                            {{ $factuur->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="bi bi-check-circle text-success fs-3"></i>
                                        <p class="mb-0">Geen openstaande facturen</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
