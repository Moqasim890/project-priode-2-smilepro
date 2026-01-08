@extends('layout.app')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-speedometer2 me-2"></i>Management Dashboard
        </h1>
        <span class="badge bg-primary fs-6">Praktijkmanagement</span>
    </div>

    {{-- Statistiek Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white-50">Afspraken Vandaag</h6>
                            <h2 class="mb-0">{{ $afsprakenStats->afspraken_vandaag ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-calendar-check fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white-50">Totale Omzet</h6>
                            <h2 class="mb-0">€{{ number_format($omzetTotaal ?? 0, 2, ',', '.') }}</h2>
                        </div>
                        <i class="bi bi-currency-euro fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-white-50">Patiënten</h6>
                            <h2 class="mb-0">{{ $totaalPatienten ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-people fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-dark-50">Medewerkers</h6>
                            <h2 class="mb-0">{{ $totaalMedewerkers ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-person-badge fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-week text-primary fs-1 mb-3"></i>
                    <h5>Afspraken Overzicht</h5>
                    <p class="text-muted small">Bekijk alle afspraken statistieken</p>
                    <a href="{{ route('admin.afspraken.overzicht') }}" class="btn btn-primary">
                        <i class="bi bi-eye me-1"></i>Bekijken
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-cash-stack text-success fs-1 mb-3"></i>
                    <h5>Omzet Overzicht</h5>
                    <p class="text-muted small">Bekijk omzet en facturen</p>
                    <a href="{{ route('admin.omzet.overzicht') }}" class="btn btn-success">
                        <i class="bi bi-eye me-1"></i>Bekijken
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill text-info fs-1 mb-3"></i>
                    <h5>Gebruikers Beheren</h5>
                    <p class="text-muted small">Beheer medewerkers en rollen</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-info">
                        <i class="bi bi-gear me-1"></i>Beheren
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-envelope-fill text-info fs-1 mb-3"></i>
                    <h5>Berichten Beheren</h5>
                    <p class="text-muted small">Beheer berichten binnen het systeem</p>
                    <a href="{{ route('admin.berichten.index') }}" class="btn btn-info">
                        <i class="bi bi-gear me-1"></i>Beheren
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Recente Afspraken --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Komende Afspraken</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Datum</th>
                                    <th>Tijd</th>
                                    <th>Patiënt</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recenteAfspraken ?? [] as $afspraak)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($afspraak->datum)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($afspraak->tijd)->format('H:i') }}</td>
                                    <td>{{ $afspraak->patientnaam }}</td>
                                    <td>
                                        <span class="badge bg-{{ $afspraak->status == 'Bevestigd' ? 'success' : 'danger' }}">
                                            {{ $afspraak->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Geen afspraken</td>
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
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Openstaande Facturen</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nummer</th>
                                    <th>Patiënt</th>
                                    <th>Bedrag</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($openstaandeFacturen ?? [] as $factuur)
                                <tr>
                                    <td>{{ $factuur->nummer }}</td>
                                    <td>{{ $factuur->patientnaam }}</td>
                                    <td>€{{ number_format($factuur->bedrag, 2, ',', '.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $factuur->status == 'Onbetaald' ? 'danger' : 'warning' }}">
                                            {{ $factuur->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Geen openstaande facturen</td>
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
