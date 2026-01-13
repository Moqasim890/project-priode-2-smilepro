{{--
Facturen Overzicht Pagina

Doel: Toon alle facturen met statistieken en filtering opties
Toegang: Praktijkmanagement, Tandarts, Mondhygiënist, Assistent rollen

Data verwacht:
- $facturen: Array van factuur objecten uit SP_GetAllFacturen
- $totalen: Associatieve array met totaalbedragen per status

Features:
- Statistiek cards per status (Verzonden, Onbetaald, Niet-Verzonden, Betaald)
- Facturen tabel met patiënt info, behandelingen, bedrag en status
- Badge kleuren per status voor visuele herkenbaarheid
--}}

@extends('layout.app')

@section('title', 'Facturen Beheren')

@section('content')
    <div class="container my-5">
        {{-- Header met titel en terug knop --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Facturen Beheren</h1>
            <a href="{{ route('medewerker.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Terug naar Dashboard
            </a>
        </div>

        {{-- Statistiek Cards - Toon totaalbedragen per status --}}
        <div class="row mb-4">
            @php
                // Configuratie voor status cards met labels en kleuren
                $statusConfig = [
                    'Verzonden' => ['label' => 'Verzonden', 'class' => 'bg-info'],
                    'Onbetaald' => ['label' => 'Onbetaald', 'class' => 'bg-danger'],
                    'Niet-Verzonden' => ['label' => 'Niet-Verzonden', 'class' => 'bg-warning'],
                    'Betaald' => ['label' => 'Betaald', 'class' => 'bg-success']
                ];
            @endphp

            {{-- Loop door elke status en toon card met totaalbedrag --}}
            @foreach($statusConfig as $status => $config)
                <div class="col-md-3">
                    <div class="card text-white {{ $config['class'] }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $config['label'] }}</h5>
                            {{-- Toon bedrag als data beschikbaar is, anders foutmelding --}}
                            @if(isset($totalen[$status]) && $totalen[$status] !== null)
                                <p class="card-text fs-3">&euro; {{ number_format($totalen[$status], 2, ',', '.') }}</p>
                            @else
                                <p class="card-text fs-6 text-white-50">Geen data beschikbaar</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex flex-row-reverse p-3 pe-0">
            <a href="{{ route('medewerker.factuur.create') }}" class="btn btn-primary">Nieuwe Factuur maken</a>
        </div>
        {{-- Facturen Tabel --}}
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Factuurnummer</th>
                                <th>Datum</th>
                                <th>Patiënt</th>
                                <th>Behandeling</th>
                                <th>Bedrag</th>
                                <th>Status</th>
                                <th>Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop door alle facturen en toon rij per factuur --}}
                            @forelse($facturen as $factuur)
                                <tr>
                                    {{-- Factuurnummer in vet --}}
                                    <td><strong>{{ $factuur->nummer }}</strong></td>

                                    {{-- Datum in Nederlands formaat (dd-mm-yyyy) --}}
                                    <td>{{ \Carbon\Carbon::parse($factuur->datum)->format('d-m-Y') }}</td>

                                    {{-- Patiënt naam uit SP via CONCAT_WS --}}
                                    <td>{{ $factuur->naam }}</td>

                                    {{-- Behandelingen uit SP via GROUP_CONCAT, of fallback tekst --}}
                                    <td>{{ $factuur->behandelingen ?? 'Geen behandeling' }}</td>

                                    {{-- Bedrag geformatteerd met euro teken --}}
                                    <td>&euro; {{ number_format($factuur->bedrag, 2, ',', '.') }}</td>

                                    {{-- Status badge met kleur per status type --}}
                                    <td>
                                        @if($factuur->status == 'Betaald')
                                            <span class="badge bg-success">{{ $factuur->status }}</span>
                                        @elseif($factuur->status == 'Onbetaald')
                                            <span class="badge bg-danger">{{ $factuur->status }}</span>
                                        @elseif($factuur->status == 'Verzonden')
                                            <span class="badge bg-info">{{ $factuur->status }}</span>
                                        @elseif($factuur->status == 'Niet-Verzonden')
                                            <span class="badge bg-warning">{{ $factuur->status }}</span>
                                        @else
                                            {{-- Fallback voor onbekende status --}}
                                            <span class="badge bg-secondary">{{ $factuur->status }}</span>
                                        @endif
                                    </td>

                                    {{-- Acties kolom - voorbereid voor toekomstige functionaliteit (bekijken, bewerken, etc.)
                                    --}}

                                    <td>
                                        <a href="" class="p-1">
                                            {{-- edit --}}
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>

                                        <a href="" class="p-2">
                                            {{-- delete --}}
                                            <i class="bi bi-trash3-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                {{-- Toon deze rij als er geen facturen zijn --}}
                                <tr>
                                    <td colspan="7" class="text-center bg-light text-muted py-4">Er zijn momenteel geen
                                        aangemaakte facturen</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
