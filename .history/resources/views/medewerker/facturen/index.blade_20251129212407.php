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

    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.facturen.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Status Filter</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Alle Statussen</option>
                        <option value="Betaald" {{ request('status') == 'Betaald' ? 'selected' : '' }}>Betaald</option>
                        <option value="Onbetaald" {{ request('status') == 'Onbetaald' ? 'selected' : '' }}>Onbetaald</option>
                        <option value="Verzonden" {{ request('status') == 'Verzonden' ? 'selected' : '' }}>Verzonden</option>
                        <option value="Niet-Verzonden" {{ request('status') == 'Niet-Verzonden' ? 'selected' : '' }}>Niet-Verzonden</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="per_page" class="form-label">Items per pagina</label>
                    <select name="per_page" id="per_page" class="form-select">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('admin.facturen.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
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
                                    <a href="{{ route('admin.facturen.edit', $factuur->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Bewerken
                                    </a>
                                    <form action="{{ route('admin.facturen.destroy', $factuur->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Weet je zeker dat je deze factuur wilt verwijderen?')">
                                            <i class="bi bi-trash"></i> Verwijderen
                                        </button>
                                    </form>
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

            <!-- Pagination -->
            @if($facturen->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        Toon {{ $facturen->firstItem() }} tot {{ $facturen->lastItem() }} van {{ $facturen->total() }} facturen
                    </div>
                    <div>
                        {{ $facturen->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
