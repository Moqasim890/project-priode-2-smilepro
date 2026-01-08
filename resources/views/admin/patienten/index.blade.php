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

    <div class="mb-2">
        <a href="{{ route('admin.patienten.create') }}" class="fs-5 text-decoration-none text-black border border-black rounded p-1">
            <i class="bi bi-person-plus me-1 p-0"></i>Nieuwe patient toevoegen
        </a>
    </div>

	<div class="border border-rounded p-4" style="max-height: 400px; overflow-y:auto;">
		@forelse($patienten as $patient)
			<div class="row d-flex flex-row mt-2 border border-black rounded p-2 h-100 mw-75">
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