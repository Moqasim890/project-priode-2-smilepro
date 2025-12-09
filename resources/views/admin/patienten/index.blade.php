@extends('layout.app')

@section('content')
<div class="container px-2 py-5">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1 class="h3 fw-bold">
			<i class="bi bi-people-fill me-2"></i>Patiënten
		</h1>
		<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
			<i class="bi bi-arrow-left me-1"></i>Terug naar dashboard
		</a>
	</div>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif
	
    {{-- <div class="row g-0 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-muted mb-2 small text-uppercase fw-semibold">Totaal Afspraken</p>
                            <h2 class="mb-0 fw-bold">add info later</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="bi bi-calendar-check text-primary fs-3"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">
                            <i class="bi bi-arrow-up-short"></i> Actieve afspraken
                        </span>
                    </div>
                </div>
            </div>
        </div>
	</div> --}}
	<div class="p-3" style="max-height: 400px; overflow-y:auto;">
		@forelse($patienten as $patient)
			<div class="row d-flex flex-row mt-3 border border-black rounded p-2 h-100 mw-75">
				<div class="col-4">
					<div class="mt-2">
						<h4>{{ $patient->volledigeNaam }}</h4>
						<p class="opacity-50 mt-0 pt-0">{{ $patient->username }}</p>
					</div>
					
					<div class="mt-2">
						<h4>Patiënt Email:</h4>
						<p class="opacity-50 mt-0 pt-0">{{ $patient->email }}</p>
					</div>
					<div class="mt-2">
						<h4>Patiënt Nummer:</h4>
						<p class="opacity-50 mt-0 pt-0">{{ $patient->nummer }}</p>
					</div>
				</div>
				<div class="col-4">
					<h4>Opmerking</h4>
					{{ $patient->opmerking }}
				</div>
				<div class="col-4">
					<h4>Medischdossier</h4>
					Voeg  medischdossier later toe
					<div class="mt-2">
						{{-- <a href="#" class="btn btn-sm btn-outline-primary" title="Bekijken">
							<i class="bi bi-eye"></i>
						</a> --}}
						<a href="#" class="btn btn-sm btn-outline-warning" title="Bewerken">
							<i class="bi bi-pencil"></i>
						</a>
						<button type="button" class="btn btn-sm btn-outline-danger" title="Verwijderen" disabled>
							<i class="bi bi-trash"></i>
						</button>
					</div>
				</div>
			</div>
		@empty
			<div class="row d-flex flex-row mt-3 border border-black rounded p-2 h-100 mw-75">
				<div class="col-12 text-center">
					<h3>
						<strong>
							Er zijn nog geen patiënten geregistreerd.
						</strong>
					</h3>
				</div>
			</div>
		@endforelse
	</div>
</div>
@endsection