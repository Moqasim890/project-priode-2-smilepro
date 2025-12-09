@extends('layout.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
        <h1 class="h4 m-0">Overzicht afspraken</h1>
        <a href="{{ url('/afspraken/create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Nieuwe afspraak
        </a>
    </div>

    {{-- Show message if no appointments --}}
    @if($afspraken->isEmpty())
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-4"></i>
            <div>
                <strong>Geen afspraken gevonden!</strong><br>
                {{ $message ?? 'Er zijn momenteel geen afspraken in het systeem.' }}
            </div>
        </div>
        <div class="text-center mt-4">
            <p class="text-muted">Wilt u een nieuwe afspraak aanmaken?</p>
            <a href="{{ url('/afspraken/create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Nieuwe afspraak maken
            </a>
        </div>
    @else
        {{-- Desktop table (large screens) --}}
        <div class="d-none d-lg-block">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Patiënt</th>
                            <th scope="col">Medewerker</th>
                            <th scope="col">Datum</th>
                            <th scope="col">Tijd</th>
                            <th scope="col">Status</th>
                            <th scope="col">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($afspraken as $afspraak)
                        <tr>
                            <th scope="row">{{ $afspraak->id }}</th>
                            <td>
                                @if($afspraak->patient && $afspraak->patient->persoon)
                                    {{ $afspraak->patient->persoon->voornaam }} 
                                    {{ $afspraak->patient->persoon->tussenvoegsel }} 
                                    {{ $afspraak->patient->persoon->achternaam }}
                                @else
                                    <span class="text-muted">Onbekend</span>
                                @endif
                            </td>
                            <td>
                                @if($afspraak->medewerker && $afspraak->medewerker->persoon)
                                    {{ $afspraak->medewerker->persoon->voornaam }} 
                                    {{ $afspraak->medewerker->persoon->tussenvoegsel }} 
                                    {{ $afspraak->medewerker->persoon->achternaam }}
                                @else
                                    <span class="text-muted">Niet toegewezen</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($afspraak->datum)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($afspraak->tijd)->format('H:i') }}</td>
                            <td>
                                <span class="badge {{ $afspraak->status === 'Bevestigd' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $afspraak->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ url('/afspraken/' . $afspraak->id) }}" class="btn btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ url('/afspraken/' . $afspraak->id . '/edit') }}" class="btn btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $afspraken->links() }}
            </div>
        </div>

        {{-- Mobile cards (small/medium screens) --}}
        <div class="d-lg-none">
            <div class="row g-3">
                @foreach($afspraken as $afspraak)
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">
                                    @if($afspraak->patient && $afspraak->patient->persoon)
                                        {{ $afspraak->patient->persoon->voornaam }} 
                                        {{ $afspraak->patient->persoon->achternaam }}
                                    @else
                                        Onbekend
                                    @endif
                                </h5>
                                <span class="badge {{ $afspraak->status === 'Bevestigd' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $afspraak->status }}
                                </span>
                            </div>
                            <p class="card-text mb-1">
                                <i class="bi bi-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($afspraak->datum)->format('d-m-Y') }} om {{ \Carbon\Carbon::parse($afspraak->tijd)->format('H:i') }}
                            </p>
                            <p class="card-text mb-2">
                                <i class="bi bi-person me-1"></i>
                                @if($afspraak->medewerker && $afspraak->medewerker->persoon)
                                    {{ $afspraak->medewerker->persoon->voornaam }} 
                                    {{ $afspraak->medewerker->persoon->achternaam }}
                                @else
                                    Niet toegewezen
                                @endif
                            </p>
                            <div class="d-flex gap-2">
                                <a href="{{ url('/afspraken/' . $afspraak->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                    <i class="bi bi-eye"></i> Bekijk
                                </a>
                                <a href="{{ url('/afspraken/' . $afspraak->id . '/edit') }}" class="btn btn-sm btn-outline-warning flex-fill">
                                    <i class="bi bi-pencil"></i> Bewerk
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $afspraken->links() }}
            </div>
        </div>
    @endif
</div>
@endsection