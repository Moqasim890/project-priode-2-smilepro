@extends('layout.app')

@section('content')
<div class="container py-5">
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
	<div class="row d-flex mt-3">
		@forelse($patienten as $patient)
			<div class="card mt-3">
				<div class="col-4">
					<h6>{{ $patient->username }}</h6>
					<p>{{ $patient->email}}</p>
					<p>{{ $patient->nummer}}</p>
				</div>
				<div class="col-4">
					{{ $patient->medischdossier}}
				</div>
				<div class="col-4">
					{{ $patient->medischdossier}}
					<a href="#" class="btn btn-sm btn-outline-primary" title="Bekijken">
						<i class="bi bi-eye"></i>
					</a>
					<a href="#" class="btn btn-sm btn-outline-warning" title="Bewerken">
						<i class="bi bi-pencil"></i>
					</a>
					<button type="button" class="btn btn-sm btn-outline-danger" title="Verwijderen" disabled>
						<i class="bi bi-trash"></i>
					</button>
				</div>					
			</div>
		@empty
			leeg
		@endforelse
	</div>
</div>
@endsection
