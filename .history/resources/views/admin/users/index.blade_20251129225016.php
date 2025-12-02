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
			<div class="px-3 py-2 d-flex justify-content-between align-items-center">
				<div>
					<small class="text-muted">Totaal: {{ $pagination['total'] ?? count($users) }} gebruikers</small>
				</div>
				<form method="GET" class="d-flex align-items-center gap-2">
					<label for="per_page" class="form-label m-0"><small>Per pagina</small></label>
					<select id="per_page" name="per_page" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
						@foreach([5,10,25,50,100] as $size)
							<option value="{{ $size }}" {{ (($pagination['per_page'] ?? request('per_page', 10)) == $size) ? 'selected' : '' }}>{{ $size }}</option>
						@endforeach
					</select>
				</form>
			</div>
			<div class="table-responsive">
				@php
					$first = !empty($users) && is_object($users[0]) ? $users[0] : null;
					$columns = $first ? array_filter(array_keys(get_object_vars($first)), fn($c) => strtolower($c) !== 'id') : [];
				@endphp
				
				<table class="table table-hover align-middle mb-0">
					<thead class="table-light">
						<tr>
							@foreach($columns as $col)
								<th scope="col">{{ ucwords(str_replace('_', ' ', $col)) }}</th>
							@endforeach
							<th scope="col" class="text-end">Acties</th>
						</tr>
					</thead>
					<tbody>
						@forelse($users as $user)
							<tr>
								@foreach($columns as $col)
									<td>
										@php $val = $user->{$col}; @endphp
										@if($val instanceof \Carbon\Carbon)
											{{ $val->format('d-m-Y H:i') }}
										@else
											{{ is_bool($val) ? ($val ? 'Ja' : 'Nee') : ($val ?? '-') }}
										@endif
									</td>
								@endforeach
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
							<tr>
								<td colspan="{{ max(1, count($columns)+1) }}" class="text-center text-muted py-4">Geen gebruikers gevonden.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>
			<div class="border-top px-3 py-2 d-flex justify-content-between align-items-center">
				@php
					$current = $pagination['current_page'] ?? 1;
					$last = $pagination['last_page'] ?? 1;
					$perPage = $pagination['per_page'] ?? request('per_page', 10);
				@endphp
				<div>
					<small class="text-muted">Pagina {{ $current }} van {{ $last }}</small>
				</div>
				<div class="btn-group">
					<a class="btn btn-sm btn-outline-secondary {{ $current <= 1 ? 'disabled' : '' }}" href="{{ request()->fullUrlWithQuery(['page' => max(1, $current-1), 'per_page' => $perPage]) }}">
						<i class="bi bi-chevron-left"></i>
					</a>
					<a class="btn btn-sm btn-outline-secondary {{ $current >= $last ? 'disabled' : '' }}" href="{{ request()->fullUrlWithQuery(['page' => min($last, $current+1), 'per_page' => $perPage]) }}">
						<i class="bi bi-chevron-right"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
