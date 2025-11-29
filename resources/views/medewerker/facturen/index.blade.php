@extends('layout.app')

@section('title', 'Facturen Beheren')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Facturen Beheren</h1>
        <a href="{{ route('medewerker.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Terug naar Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        @php
            $statusConfig = [
                'Verzonden' => ['label' => 'Verzonden', 'class' => 'bg-info'],
                'Onbetaald' => ['label' => 'Onbetaald', 'class' => 'bg-danger'],
                'Niet-Verzonden' => ['label' => 'Niet-Verzonden', 'class' => 'bg-warning'],
                'Betaald' => ['label' => 'Betaald', 'class' => 'bg-success']
            ];
        @endphp
        
        @foreach($statusConfig as $status => $config)
            <div class="col-md-3">
                <div class="card text-white {{ $config['class'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $config['label'] }}</h5>
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

    <!-- Facturen Table -->
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
                        @forelse($facturen as $factuur)
                            <tr>
                                <td><strong>{{ $factuur->nummer }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($factuur->datum)->format('d-m-Y') }}</td>
                                <td>{{ $factuur->naam }}</td>
                                <td>{{ $factuur->behandelingen ?? 'Geen behandeling' }}</td>
                                <td>&euro; {{ number_format($factuur->bedrag, 2, ',', '.') }}</td>
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
                                        <span class="badge bg-secondary">{{ $factuur->status }}</span>
                                    @endif
                                </td>
                                <td>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center bg-light text-muted py-4">Er zijn momenteel geen aangemaakte facturen</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
