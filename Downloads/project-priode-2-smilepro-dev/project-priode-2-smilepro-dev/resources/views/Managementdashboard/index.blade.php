
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
                            <h3 class="mb-0">127</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-check text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-success"><i class="bi bi-arrow-up"></i> 12% vs vorige maand</small>
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
                            <h3 class="mb-0">8</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-event text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">5 bevestigd, 3 wachtend</small>
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
                            <h3 class="mb-0">34</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-week text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Ma-Vr geplanned</small>
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
                            <h3 class="mb-0">5</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="bi bi-calendar-x text-danger fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-danger"><i class="bi bi-arrow-down"></i> 3% vs vorige maand</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts and Tables --}}
    <div class="row g-3">
        {{-- Afspraken per dag (laatste 7 dagen) --}}
        <div class="col-12 col-lg-8">
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
                                    <th>Dag</th>
                                    <th class="text-center">Bevestigd</th>
                                    <th class="text-center">Geannuleerd</th>
                                    <th class="text-center">Totaal</th>
                                    <th>Trend</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>02-12-2025</td>
                                    <td><span class="badge bg-primary">Maandag</span></td>
                                    <td class="text-center">12</td>
                                    <td class="text-center">1</td>
                                    <td class="text-center"><strong>13</strong></td>
                                    <td><i class="bi bi-arrow-up text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>01-12-2025</td>
                                    <td><span class="badge bg-secondary">Zondag</span></td>
                                    <td class="text-center">0</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center"><strong>0</strong></td>
                                    <td><i class="bi bi-dash text-muted"></i></td>
                                </tr>
                                <tr>
                                    <td>30-11-2025</td>
                                    <td><span class="badge bg-secondary">Zaterdag</span></td>
                                    <td class="text-center">3</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center"><strong>3</strong></td>
                                    <td><i class="bi bi-arrow-down text-warning"></i></td>
                                </tr>
                                <tr>
                                    <td>29-11-2025</td>
                                    <td><span class="badge bg-primary">Vrijdag</span></td>
                                    <td class="text-center">15</td>
                                    <td class="text-center">2</td>
                                    <td class="text-center"><strong>17</strong></td>
                                    <td><i class="bi bi-arrow-up text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>28-11-2025</td>
                                    <td><span class="badge bg-primary">Donderdag</span></td>
                                    <td class="text-center">14</td>
                                    <td class="text-center">1</td>
                                    <td class="text-center"><strong>15</strong></td>
                                    <td><i class="bi bi-arrow-up text-success"></i></td>
                                </tr>
                                <tr>
                                    <td>27-11-2025</td>
                                    <td><span class="badge bg-primary">Woensdag</span></td>
                                    <td class="text-center">11</td>
                                    <td class="text-center">0</td>
                                    <td class="text-center"><strong>11</strong></td>
                                    <td><i class="bi bi-dash text-muted"></i></td>
                                </tr>
                                <tr>
                                    <td>26-11-2025</td>
                                    <td><span class="badge bg-primary">Dinsdag</span></td>
                                    <td class="text-center">13</td>
                                    <td class="text-center">1</td>
                                    <td class="text-center"><strong>14</strong></td>
                                    <td><i class="bi bi-arrow-up text-success"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Medewerkers --}}
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="mb-0">Top Medewerkers</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Dr. Piet Pietersen</h6>
                                    <small class="text-muted">Tandarts</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">47</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Dr. Anna Bakker</h6>
                                    <small class="text-muted">Tandarts</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">42</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Lisa de Jong</h6>
                                    <small class="text-muted">Mondhygiënist</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">28</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0 px-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Mark Hendriks</h6>
                                    <small class="text-muted">Assistent</small>
                                </div>
                                <span class="badge bg-primary rounded-pill">10</span>
                            </div>
                        </div>
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