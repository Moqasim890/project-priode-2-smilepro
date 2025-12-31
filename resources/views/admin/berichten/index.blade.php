@extends('layout.app')

@section('content')
<div class="container py-5">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1 class="h3 fw-bold">
			<i class="bi bi-bell me-2"></i>Berichten
		</h1>
		<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
			<i class="bi bi-arrow-left me-1"></i>Terug naar dashboard
		</a>
	</div>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

    <div class="border p-2 shadow-sm overflow-y-auto" style="max-height: 60dvh; height: 60dvh;">
		@forelse($berichten as $bericht)
			<div class="card shadow-sm mt-2">
				<div class="card-header">
					<h1>test</h1>
				</div>
				<div class="card-body p-4">
					<div class="row">
						<div class="col-6">
							<h6>Van: 
								@if ( $bericht->medewerkerid == NULL || "" )
									SYSTEEM
								@else 
									{{ $bericht->medewerkerNaam }}
								@endif
							</h6>
							<h6>Aan: 
								@if ( $bericht->patientNaam == $naam )
									U
								@else 
									{{ $bericht->patientNaam }}
								@endif
							</h6>
							<p class="m-0">{{ $bericht->bericht }}</p>
						</div>
						<div class="col-6 d-flex flex-row justify-content-end align-items-center gap-1">
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
				</div>
			</div>
		@empty
			<h6>U heeft momenteel nog geen meldingen.</h6>
		@endforelse
	</div>
</div>
@endsection