@extends('layout.app')

{{--
    Admin Dashboard View
    Doel: Hoofdpagina voor praktijkmanagement met snelkoppelingen naar beheertools
    Toegang: Alleen Praktijkmanagement rol
    Functies: Navigatie naar gebruikersbeheer, facturen, statistieken
--}}

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 fw-bold">
            <i class="bi bi-speedometer2 me-2"></i>Management Dashboard
        </h1>
        <span class="badge bg-primary">Praktijkmanagement</span>
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
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">Beheren</a>
                </div>
            </div>
        </div>
        
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
                    <a href="{{ route('medewerker.factuur.index') }}" class="btn btn-outline-success btn-sm">Beheren</a>
                </div>
            </div>
        </div>
<<<<<<< HEAD

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger bg-opacity-10 rounded p-3 me-3">
                            <i class="bi bi-person-standing text-danger fs-3"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Patiënten beheren</h5>
                        </div>
                    </div>
                    <p class="card-text text-muted">Beheer alle Patiënten</p>
                    <a href="{{ route('admin.patienten.index') }}" class="btn btn-outline-danger btn-sm">Beheren</a>
                </div>
            </div>
        </div>
=======
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
        
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
