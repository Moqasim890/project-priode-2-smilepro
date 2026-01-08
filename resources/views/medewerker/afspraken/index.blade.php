{{--
    Afspraken Overzicht Pagina
    
    Doel: Toon alle afspraken met statistieken en filtering opties
    Toegang: Praktijkmanagement, Tandarts, Mondhygiënist, Assistent rollen
    
    Data verwacht:
    - $afspraken: Array van afspraak objecten uit SP_GetAllAfspraken
    - $statistieken: Object met afspraken statistieken
    
    Features:
    - Statistiek cards (vandaag, morgen, week, bevestigd, geannuleerd)
    - Afspraken tabel met patiënt en medewerker info
    - Badge kleuren per status voor visuele herkenbaarheid
--}}

@extends('layout.app')

@section('title', 'Afspraken Beheren')

@section('content')
@php(
    $isAdmin = request()->routeIs('admin.*');
    $afsprakenPrefix = $isAdmin ? 'admin.afspraken' : 'medewerker.afspraken';
    $dashboardRoute = $isAdmin ? 'admin.dashboard' : 'medewerker.dashboard';
)

<div class="container py-4">
    {{-- Header met titel en acties - BOVENAAN --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-calendar-check me-2"></i>Afspraken Beheren
        </h1>
        <div>
            <a href="{{ route($afsprakenPrefix . '.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Nieuwe Afspraak
            </a>
            <a href="{{ route($dashboardRoute) }}" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Terug
            </a>
        </div>
    </div>

    {{-- Success/Error meldingen --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Statistiek Cards --}}
    <div class="row mb-4">
        {{-- Afspraken Vandaag --}}
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-day fs-2 mb-2"></i>
                    <h5 class="card-title">Vandaag</h5>
                    <p class="card-text fs-3 fw-bold">{{ $statistieken->afspraken_vandaag ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Afspraken Morgen --}}
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card text-white bg-info h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-plus fs-2 mb-2"></i>
                    <h5 class="card-title">Morgen</h5>
                    <p class="card-text fs-3 fw-bold">{{ $statistieken->afspraken_morgen ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Afspraken Deze Week --}}
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card text-white bg-secondary h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar-week fs-2 mb-2"></i>
                    <h5 class="card-title">Deze Week</h5>
                    <p class="card-text fs-3 fw-bold">{{ $statistieken->afspraken_week ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Bevestigde Afspraken --}}
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle fs-2 mb-2"></i>
                    <h5 class="card-title">Bevestigd</h5>
                    <p class="card-text fs-3 fw-bold">{{ $statistieken->bevestigd ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Geannuleerde Afspraken --}}
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body text-center">
                    <i class="bi bi-x-circle fs-2 mb-2"></i>
                    <h5 class="card-title">Geannuleerd</h5>
                    <p class="card-text fs-3 fw-bold">{{ $statistieken->geannuleerd ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Totaal Afspraken --}}
        <div class="col-md-4 col-lg-2 mb-3">
            <div class="card text-white bg-dark h-100">
                <div class="card-body text-center">
                    <i class="bi bi-calendar3 fs-2 mb-2"></i>
                    <h5 class="card-title">Totaal</h5>
                    <p class="card-text fs-3 fw-bold">{{ $statistieken->totaal_afspraken ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Afspraken Tabel --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-table me-2"></i>Alle Afspraken</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Datum</th>
                            <th>Tijd</th>
                            <th>Patiënt</th>
                            <th>Medewerker</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Opmerking</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($afspraken as $afspraak)
                            <tr>
                                {{-- Datum met icon --}}
                                <td>
                                    <i class="bi bi-calendar3 text-muted me-1"></i>
                                    {{ \Carbon\Carbon::parse($afspraak->datum)->format('d-m-Y') }}
                                    @if(\Carbon\Carbon::parse($afspraak->datum)->isToday())
                                        <span class="badge bg-primary ms-1">Vandaag</span>
                                    @elseif(\Carbon\Carbon::parse($afspraak->datum)->isTomorrow())
                                        <span class="badge bg-info ms-1">Morgen</span>
                                    @elseif(\Carbon\Carbon::parse($afspraak->datum)->isPast())
                                        <span class="badge bg-secondary ms-1">Verleden</span>
                                    @endif
                                </td>
                                
                                {{-- Tijd --}}
                                <td>
                                    <i class="bi bi-clock text-muted me-1"></i>
                                    {{ \Carbon\Carbon::parse($afspraak->tijd)->format('H:i') }}
                                </td>
                                
                                {{-- Patiënt --}}
                                <td>
                                    <strong>{{ $afspraak->patientnaam }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $afspraak->patientnummer }}</small>
                                </td>
                                
                                {{-- Medewerker --}}
                                <td>
                                    @if($afspraak->medewerkernaam)
                                        {{ $afspraak->medewerkernaam }}
                                        <br>
                                        <small class="text-muted">{{ $afspraak->medewerkernummer }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                
                                {{-- Medewerker Type/Specialisatie --}}
                                <td>
                                    @if($afspraak->medewerkertype)
                                        <span class="badge bg-light text-dark">{{ $afspraak->medewerkertype }}</span>
                                        @if($afspraak->specialisatie)
                                            <br><small class="text-muted">{{ $afspraak->specialisatie }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                
                                {{-- Status badge --}}
                                <td>
                                    @if($afspraak->status == 'Bevestigd')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>{{ $afspraak->status }}
                                        </span>
                                    @elseif($afspraak->status == 'Geannuleerd')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>{{ $afspraak->status }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $afspraak->status }}</span>
                                    @endif
                                </td>
                                
                                {{-- Opmerking --}}
                                <td>
                                    @if($afspraak->opmerking)
                                        <span class="text-truncate d-inline-block" style="max-width: 150px;" 
                                              title="{{ $afspraak->opmerking }}">
                                            {{ $afspraak->opmerking }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                
                                {{-- Acties --}}
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        {{-- Bekijken knop - voor toekomstige functionaliteit --}}
                                        <button type="button" class="btn btn-outline-info" title="Bekijken" disabled>
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        {{-- Bewerken knop - voor toekomstige functionaliteit --}}
                                        <button type="button" class="btn btn-outline-warning" title="Bewerken" disabled>
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        {{-- Annuleren knop - voor toekomstige functionaliteit --}}
                                        <button type="button" class="btn btn-outline-danger" title="Annuleren" disabled>
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
                                        <p class="mb-2">Er zijn momenteel geen afspraken</p>
                                        <a href="{{ route($afsprakenPrefix . '.create') }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-plus-lg me-1"></i> Nieuwe Afspraak Maken
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
