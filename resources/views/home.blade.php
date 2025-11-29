@extends('layout.app')

@section('content')
<section class="py-5 bg-light border-bottom">
	<div class="container py-4">
		<div class="row align-items-center">
			<div class="col-lg-6 mb-4 mb-lg-0">
				<h1 class="display-5 fw-bold mb-3">Welkom bij <span class="text-primary">SmilePro</span></h1>
				<p class="lead">Uw tandheelkunde praktijk voor een stralende glimlach. Maak eenvoudig een afspraak en ontdek onze professionele zorg.</p>
				<div class="d-flex gap-3 mt-4">
					<a href="#diensten" class="btn btn-primary btn-lg">Onze diensten</a>
					<a href="#contact" class="btn btn-outline-primary btn-lg">Contact</a>
				</div>
			</div>
			<div class="col-lg-6 text-center">
				<div class="ratio ratio-16x9 rounded shadow overflow-hidden">
					<img src="https://images.unsplash.com/photo-1609840114035-19664fca9871?auto=format&fit=crop&w=1200&q=60" alt="Tandheelkunde" class="w-100 h-100 object-fit-cover">
				</div>
			</div>
		</div>
	</div>
</section>

<section id="diensten" class="py-5">
	<div class="container">
		<h2 class="fw-bold mb-4">Wat wij bieden</h2>
		<div class="row g-4">
			<div class="col-md-4">
				<div class="card h-100 shadow-sm">
					<div class="card-body">
						<h5 class="card-title fw-semibold">Controles & Preventie</h5>
						<p class="card-text">Regelmatige controles, tandsteen verwijderen en advies voor optimale mondgezondheid.</p>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card h-100 shadow-sm">
					<div class="card-body">
						<h5 class="card-title fw-semibold">Cosmetische Behandelingen</h5>
						<p class="card-text">Professioneel bleken, facings en esthetische correcties voor een mooie glimlach.</p>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card h-100 shadow-sm">
					<div class="card-body">
						<h5 class="card-title fw-semibold">Restauraties</h5>
						<p class="card-text">Vullingen, kronen en bruggen met duurzame materialen en precisie.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="contact" class="py-5 bg-body-tertiary">
	<div class="container">
		<h2 class="fw-bold mb-3">Direct een afspraak?</h2>
		<p class="mb-4">Neem contact op voor beschikbaarheid of stel uw vraag. Wij reageren snel.</p>
		<a href="{{ url('/contact') }}" class="btn btn-primary btn-lg">Neem contact op</a>
	</div>
</section>
@endsection