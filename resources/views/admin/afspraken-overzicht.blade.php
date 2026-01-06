@extends('layout.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-calendar-week me-2"></i>Afspraken Overzicht
        </h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Terug
        </a>
    </div>

    {{-- Statistiek Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $afsprakenStats->totaal_afspraken ?? 0 }}</h3>
                    <small>Totaal</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $afsprakenStats->afspraken_vandaag ?? 0 }}</h3>
                    <small>Vandaag</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $afsprakenStats->afspraken_morgen ?? 0 }}</h3>
                    <small>Morgen</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $afsprakenStats->afspraken_week ?? 0 }}</h3>
                    <small>Deze Week</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $afsprakenStats->bevestigd ?? 0 }}</h3>
                    <small>Bevestigd</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $afsprakenStats->geannuleerd ?? 0 }}</h3>
                    <small>Geannuleerd</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Afspraken per Maand --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Afspraken per Maand</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Maand</th>
                                    <th class="text-center">Totaal</th>
                                    <th class="text-center">Bevestigd</th>
                                    <th class="text-center">Geannuleerd</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($afsprakenPerMaand ?? [] as $maand)
                                <tr>
                                    <td>{{ $maand->maand_naam }}</td>
                                    <td class="text-center">{{ $maand->totaal }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">{{ $maand->bevestigd }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">{{ $maand->geannuleerd }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Geen data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Afspraken per Medewerker --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Afspraken per Medewerker</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Medewerker</th>
                                    <th>Functie</th>
                                    <th class="text-center">Aantal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($afsprakenPerMedewerker ?? [] as $medewerker)
                                <tr>
                                    <td>{{ $medewerker->naam }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $medewerker->medewerkertype }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $medewerker->aantal_afspraken }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Geen medewerkers</td>
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
