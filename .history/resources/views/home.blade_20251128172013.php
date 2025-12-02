@extends('layout.app')
@section('content')
    <!-- HERO -->
    <section class="hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Voor een gezonde & stralende glimlach</h1>
            <p class="lead mb-4">SmilePro Tandartspraktijk – deskundige zorg, moderne technieken en persoonlijke aandacht.</p>
            <a href="#contact" class="btn btn-light btn-lg me-2"><i class="bi bi-calendar-check me-1"></i> Afspraak maken</a>
            <a href="#diensten" class="btn btn-outline-light btn-lg"><i class="bi bi-boxes me-1"></i> Onze diensten</a>
        </div>
    </section>

    <!-- DIENSTEN -->
    <section id="diensten" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Onze Tandheelkundige Diensten</h2>
                <p class="text-muted">Complete mondzorg voor het hele gezin.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm service-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-shield-check text-primary me-2"></i>Preventieve Zorg</h5>
                            <p class="card-text small text-muted">Periodieke controles, professionele reiniging en poetsadvies om problemen te voorkomen.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm service-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-braces text-primary me-2"></i>Orthodontie</h5>
                            <p class="card-text small text-muted">Beugels & aligners voor een mooi recht gebit afgestemd op jouw situatie.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm service-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-star text-primary me-2"></i>Esthetische Behandelingen</h5>
                            <p class="card-text small text-muted">Bleken, facings en cosmetische correcties voor een esthetische glimlach.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm service-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bi bi-lightning-fill text-primary me-2"></i>Spoedhulp</h5>
                            <p class="card-text small text-muted">Bij acute pijn of een ongeluk helpen wij je zo snel mogelijk verder.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- TEAM -->
    <section id="team" class="py-5 bg-white border-top">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Ons Team</h2>
                <p class="text-muted">Professioneel, vriendelijk en gedreven door kwaliteit.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://placehold.co/600x400?text=Tandarts" alt="Hoofdtandarts" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title mb-1">Dr. Jansen</h5>
                            <small class="text-muted d-block mb-2">Tandarts & Praktijkhouder</small>
                            <p class="card-text small">Gespecialiseerd in restauratieve tandheelkunde en patiëntgerichte zorg.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://placehold.co/600x400?text=Orthodontist" alt="Orthodontist" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title mb-1">Drs. Vermeulen</h5>
                            <small class="text-muted d-block mb-2">Orthodontist</small>
                            <p class="card-text small">Expert in moderne beugeltherapie en aligner systemen.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://placehold.co/600x400?text=Assistente" alt="Assistente" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title mb-1">Sanne</h5>
                            <small class="text-muted d-block mb-2">Preventie-assistente</small>
                            <p class="card-text small">Helpt bij reiniging, poetsinstructies en angstbegeleiding.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-5">
        <div class="container">
            <div class="p-5 bg-primary text-white rounded-3 shadow-sm d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                <div>
                    <h3 class="fw-bold mb-2">Direct een afspraak plannen?</h3>
                    <p class="mb-0">Wij nemen de tijd om jouw gebit optimaal gezond te houden.</p>
                </div>
                <a href="#contact" class="btn btn-light btn-lg"><i class="bi bi-phone me-1"></i> Neem contact op</a>
            </div>
        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="py-5 bg-white border-top">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <h4 class="fw-bold mb-3">Contactgegevens</h4>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Hoofdstraat 12, 3012 AB Rotterdam</li>
                        <li class="mb-2"><i class="bi bi-telephone-fill text-primary me-2"></i>010 - 123 45 67</li>
                        <li class="mb-2"><i class="bi bi-envelope-fill text-primary me-2"></i>info@smilepro.nl</li>
                        <li class="mb-2"><i class="bi bi-clock-fill text-primary me-2"></i>Ma–Vr: 08:00 - 17:00</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h4 class="fw-bold mb-3">Vraag / afspraak verzoek</h4>
                    <form method="POST" action="#">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Naam</label>
                            <input type="text" class="form-control" placeholder="Uw naam" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">E‑mail</label>
                            <input type="email" class="form-control" placeholder="naam@voorbeeld.nl" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bericht</label>
                            <textarea class="form-control" rows="4" placeholder="Uw vraag of wens" required></textarea>
                        </div>
                        <button class="btn btn-primary"><i class="bi bi-send me-1"></i> Versturen</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
