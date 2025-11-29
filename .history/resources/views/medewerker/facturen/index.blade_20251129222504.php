@extends('layout.app')

@section('title', 'Facturen Beheren')

@section('content')
<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Facturen Beheren</h1>
        <a href="{{ route('admin.facturen.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nieuwe Factuur
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
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Totaal Bedrag</h5>
                    <p class="card-text fs-3">&euro; {{ number_format($totalBedrag, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Betaald</h5>
                    <p class="card-text fs-3">&euro; {{ number_format($betaaldBedrag, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Openstaand</h5>
                    <p class="card-text fs-3">&euro; {{ number_format($openstaandBedrag, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
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
                                <td>
                                    {{ $factuur->patient_naam }}<br>
                                    <small class="text-muted">{{ $factuur->patient_nummer }}</small>
                                </td>
                                <td>{{ $factuur->behandelingtype }}</td>
                                <td>&euro; {{ number_format($factuur->bedrag, 2, ',', '.') }}</td>
                                <td>
                                    @if($factuur->status == 'Betaald')
                                        <span class="badge bg-success">{{ $factuur->status }}</span>
                                    @elseif($factuur->status == 'Onbetaald')
                                        <span class="badge bg-danger">{{ $factuur->status }}</span>
                                    @elseif($factuur->status == 'Verzonden')
                                        <span class="badge bg-info">{{ $factuur->status }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $factuur->status }}</span>
                                    @endif
                                </td>
                                <td>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Geen facturen gevonden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
