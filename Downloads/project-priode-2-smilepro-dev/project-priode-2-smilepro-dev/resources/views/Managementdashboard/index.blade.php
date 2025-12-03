@extends('layout.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="mb-4">
        <h1 class="h3 mb-1">Management Dashboard</h1>
        <p class="text-muted">Overzicht van belangrijke statistieken en gegevens</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Totaal Afspraken</p>
                            <h3 class="mb-0">{{ $stats['totaal'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-check text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Vandaag</p>
                            <h3 class="mb-0">{{ $stats['vandaag'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-event text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Deze Week</p>
                            <h3 class="mb-0">{{ $stats['dezeWeek'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-week text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Geannuleerd</p>
                            <h3 class="mb-0">{{ $stats['geannuleerd'] }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-x text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Afspraken per dag --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="mb-0">Afspraken per Dag (Laatste 7 Dagen)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Datum</th>
                                    <th class="text-center">Bevestigd</th>
                                    <th class="text-center">Geannuleerd</th>
                                    <th class="text-center">Totaal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lastSevenDays as $day)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($day->dag)->format('d-m-Y') }}</td>
                                    <td class="text-center">{{ $day->bevestigd }}</td>
                                    <td class="text-center">{{ $day->geannuleerd }}</td>
                                    <td class="text-center"><strong>{{ $day->totaal }}</strong></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Geen data beschikbaar</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-3 mt-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="mb-0">Snelle Acties</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('afspraken.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-calendar-check d-block fs-3 mb-2"></i>
                                Alle Afspraken
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="#" class="btn btn-outline-success w-100">
                                <i class="bi bi-plus-circle d-block fs-3 mb-2"></i>
                                Nieuwe Afspraak
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="#" class="btn btn-outline-info w-100">
                                <i class="bi bi-people d-block fs-3 mb-2"></i>
                                Patiënten
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="#" class="btn btn-outline-warning w-100">
                                <i class="bi bi-file-earmark-text d-block fs-3 mb-2"></i>
                                Rapporten
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection