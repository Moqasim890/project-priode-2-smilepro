{{--
    Gebruikersbeheer View
    Doel: Overzicht van alle gebruikers in het systeem met paginering
    Toegang: Alleen Praktijkmanagement rol
    Data verwacht:
        - $users: Array van user objecten uit SP_GetAllUsers
        - $pagination: Array met current_page, last_page, total, per_page
    Features: Dynamische tabel, paginering, per-page selectie, acties (bekijken/bewerken)
--}}
@extends('layout.app')

@section('content')
<div class="container py-5">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1 class="h3 fw-bold">
			<i class="bi bi-people-fill me-2"></i>Gebruikers
		</h1>
		<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
			<i class="bi bi-arrow-left me-1"></i>Terug naar dashboard
		</a>
	</div>

	@if(session('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

	<div class="card shadow-sm">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead class="table-light">
						<tr>
							<th scope="col">Gebruikersnaam</th>
							<th scope="col">Email</th>
							<th scope="col">Nummer</th>
							<th scope="col">Medischdossier</th>
							<th scope="col" class="text-end">Acties</th>
						</tr>
					</thead>
					<tbody>
						@forelse($patienten as $patient)
							<tr>
                                <td>{{ $patient->username }}</td>
                                <td>{{ $patient->email}}</td>
                                <td>{{ $patient->nummer}}</td>
                                <td>{{ $patient->medischdossier}}</td>
								<td class="text-end">
									<a href="#" class="btn btn-sm btn-outline-primary" title="Bekijken">
										<i class="bi bi-eye"></i>
									</a>
									<a href="#" class="btn btn-sm btn-outline-warning" title="Bewerken">
										<i class="bi bi-pencil"></i>
									</a>
									<button type="button" class="btn btn-sm btn-outline-danger" title="Verwijderen" disabled>
										<i class="bi bi-trash"></i>
									</button>
								</td>
							</tr>
						@empty
                            leeg
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection
