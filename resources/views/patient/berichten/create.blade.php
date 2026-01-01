@extends('layout.app')

@section('content')
<div class="container py-5">
	<div class="d-flex justify-content-between align-items-center mb-4">
		<h1 class="h3 fw-bold">
			<i class="bi bi-envelope-plus me-2"></i>Bericht opstellen
		</h1>
		<a href="{{ route('Patient.berichten.index') }}" class="btn btn-outline-secondary btn-sm">
			<i class="bi bi-arrow-left me-1"></i>Terug
		</a>
	</div>

    <div class="border p-2 shadow-sm overflow-y-auto" style="max-height: 60dvh; height: 55dvh;">
        <form action="" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Aan:</label>
                <input type="email" class="form-control" id="email" placeholder="name@example.com">
            </div>
            <div class="mb-3">
                <label for="Bericht" class="form-label">Bericht:</label>
                <textarea class="form-control" id="Bericht" placeholder="..." rows="10"></textarea>
            </div>
            <div class="d-flex">
                <button type="submit" class="btn btn-primary ms-auto">
                    <i class="bi bi-send me-1"></i>Verzenden
                </button>
            </div>
        </form>
    </div>
</div>
@endsection