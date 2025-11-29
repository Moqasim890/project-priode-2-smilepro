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
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-calendar-check text-primary fs-3"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Afspraken beheren</h5>
                        </div>
                    </div>
                    <p class="card-text text-muted">Bekijk en beheer afspraken van klanten</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Bekijken</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-people text-success fs-3"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Klanten</h5>
                        </div>
                    </div>
                    <p class="card-text text-muted">Bekijk klantinformatie en geschiedenis</p>
                    <a href="#" class="btn btn-outline-success btn-sm">Bekijken</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-clock-history me-2"></i>Recente activiteit</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Recente afspraken en acties worden hier weergegeven...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
