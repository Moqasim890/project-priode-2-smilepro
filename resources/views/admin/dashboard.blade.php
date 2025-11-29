@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
        </h1>
        <span class="badge bg-primary">Administrator</span>
    </div>
    
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-people-fill text-primary fs-3"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Gebruikers beheren</h5>
                        </div>
                    </div>
                    <p class="card-text text-muted">Beheer alle gebruikers in het systeem</p>
                    <a href="#" class="btn btn-outline-primary btn-sm">Beheren</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-shield-check text-success fs-3"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Rollen beheren</h5>
                        </div>
                    </div>
                    <p class="card-text text-muted">Wijs rollen toe aan gebruikers</p>
                    <a href="#" class="btn btn-outline-success btn-sm">Beheren</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-gear-fill text-warning fs-3"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Systeem instellingen</h5>
                        </div>
                    </div>
                    <p class="card-text text-muted">Configureer het systeem</p>
                    <a href="#" class="btn btn-outline-warning btn-sm">Beheren</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0"><i class="bi bi-graph-up me-2"></i>Statistieken</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Dashboard statistieken worden hier weergegeven...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
