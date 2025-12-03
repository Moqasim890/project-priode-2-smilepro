@extends('layout.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    {{-- Header --}}
    <div class="mb-4">
        <h1 class="h2 fw-bold mb-2">Management Dashboard</h1>
        <p class="text-muted mb-0">Overzicht van belangrijke statistieken en gegevens</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted mb-2 small text-uppercase fw-semibold">Totaal Afspraken</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['totaal'] }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-calendar-check text-primary fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">
                            <i class="bi bi-arrow-up-short"></i> Actieve afspraken
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted mb-2 small text-uppercase fw-semibold">Vandaag</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['vandaag'] }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-calendar-event text-success fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                            <i class="bi bi-clock"></i> {{ \Carbon\Carbon::now()->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted mb-2 small text-uppercase fw-semibold">Deze Week</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['dezeWeek'] }}</h2>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-calendar-week text-info fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1">
                            <i class="bi bi-calendar-range"></i> Week {{ \Carbon\Carbon::now()->week }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted mb-2 small text-uppercase fw-semibold">Geannuleerd</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['geannuleerd'] }}</h2>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-calendar-x text-danger fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        @php
                            $percentage = $stats['totaal'] > 0 ? round(($stats['geannuleerd'] / $stats['totaal']) * 100, 1) : 0;
                        @endphp
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1">
                            <i class="bi bi-percent"></i> {{ $percentage }}% van totaal
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-3 mb-4">
        {{-- Afspraken per dag tabel --}}
        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                            Afspraken per Dag
                        </h5>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                            Laatste 7 dagen
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4 py-3 border-0">Datum</th>
                                    <th class="text-center py-3 border-0">
                                        <i class="bi bi-check-circle text-success me-1"></i>Bevestigd
                                    </th>
                                    <th class="text-center py-3 border-0">
                                        <i class="bi bi-x-circle text-danger me-1"></i>Geannuleerd
                                    </th>
                                    <th class="text-center py-3 border-0 fw-bold">Totaal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lastSevenDays as $day)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3">
                                                <i class="bi bi-calendar-day text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ \Carbon\Carbon::parse($day->dag)->format('d M Y') }}</div>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($day->dag)->locale('nl')->isoFormat('dddd') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center py-3">
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">
                                            {{ $day->bevestigd }}
                                        </span>
                                    </td>
                                    <td class="text-center py-3">
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 fw-semibold">
                                            {{ $day->geannuleerd }}
                                        </span>
                                    </td>
                                    <td class="text-center py-3">
                                        <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold">
                                            {{ $day->totaal }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5">
                                        <i class="bi bi-inbox text-muted fs-1 d-block mb-2"></i>
                                        <p class="text-muted mb-0">Geen data beschikbaar voor de laatste 7 dagen</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Stats Sidebar --}}
        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-activity me-2 text-success"></i>
                        Statistieken Overzicht
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Bezettingsgraad Vandaag</span>
                            <span class="fw-bold text-success">
                                @php
                                    $bezetting = min(($stats['vandaag'] / 10) * 100, 100);
                                @endphp
                                {{ round($bezetting) }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $bezetting }}%"></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">Succes Ratio</span>
                            <span class="fw-bold text-primary">
                                @php
                                    $successRatio = $stats['totaal'] > 0 ? round((($stats['totaal'] - $stats['geannuleerd']) / $stats['totaal']) * 100, 1) : 0;
                                @endphp
                                {{ $successRatio }}%
                            </span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $successRatio }}%"></div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-check-circle text-primary"></i>
                            </div>
                            <span class="text-muted">Bevestigd</span>
                        </div>
                        <span class="fw-bold">{{ $stats['totaal'] - $stats['geannuleerd'] }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-clock-history text-warning"></i>
                            </div>
                            <span class="text-muted">Aankomend</span>
                        </div>
                        <span class="fw-bold">{{ $stats['dezeWeek'] - $stats['vandaag'] }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                                <i class="bi bi-graph-up text-info"></i>
                            </div>
                            <span class="text-muted">Gemiddeld/Dag</span>
                        </div>
                        <span class="fw-bold">{{ $lastSevenDays->count() > 0 ? round($lastSevenDays->avg('totaal'), 1) : 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-lightning-charge me-2 text-warning"></i>
                        Snelle Acties
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <a href="{{ route('afspraken.index') }}" class="btn btn-outline-primary w-100 py-3 hover-lift text-decoration-none">
                                <i class="bi bi-calendar-check d-block fs-1 mb-2"></i>
                                <span class="fw-semibold">Alle Afspraken</span>
                                <small class="d-block text-muted mt-1">{{ $stats['totaal'] }} totaal</small>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="#" class="btn btn-outline-success w-100 py-3 hover-lift text-decoration-none">
                                <i class="bi bi-plus-circle d-block fs-1 mb-2"></i>
                                <span class="fw-semibold">Nieuwe Afspraak</span>
                                <small class="d-block text-muted mt-1">Snel aanmaken</small>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="#" class="btn btn-outline-info w-100 py-3 hover-lift text-decoration-none">
                                <i class="bi bi-people d-block fs-1 mb-2"></i>
                                <span class="fw-semibold">Patiënten</span>
                                <small class="d-block text-muted mt-1">Beheer lijst</small>
                            </a>
                        </div>
                        <div class="col-6 col-md-3">
                            <a href="#" class="btn btn-outline-warning w-100 py-3 hover-lift text-decoration-none">
                                <i class="bi bi-file-earmark-bar-graph d-block fs-1 mb-2"></i>
                                <span class="fw-semibold">Rapporten</span>
                                <small class="d-block text-muted mt-1">Exporteer data</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection