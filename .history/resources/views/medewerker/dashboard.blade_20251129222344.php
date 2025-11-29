@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-briefcase me-2"></i>Medewerker Dashboard
        </h1>
        <span class="badge bg-info">Medewerker</span>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-receipt-cutoff text-success fs-3"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Facturen beheren</h5>
                        </div>
                    </div>
                    <p class="card-text text-muted">Beheer alle facturen en betalingen</p>
                    <a href="{{ route('admin.factuur.index') }}" class="btn btn-outline-success btn-sm">Beheren</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
