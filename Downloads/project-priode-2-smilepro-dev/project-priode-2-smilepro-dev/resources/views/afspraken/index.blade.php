@extends('layout.app')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
  <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
    <h1 class="h4 m-0">Overzicht afspraken</h1>
    <a href="{{ url('/afspraken/create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-circle"></i> Nieuwe afspraak
    </a>
  </div>

  {{-- Desktop table (large screens) --}}
  <div class="d-none d-lg-block">{{-- calss d-none d-lg-block--}}
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Patiënt</th>
            <th scope="col">Medewerker</th>
            <th scope="col">Datum</th>
            <th scope="col">Tijd</th>
            <th scope="col">Status</th>
            <th scope="col">Actief</th>
            <th scope="col">Acties</th>
          </tr>
        </thead>
        <tbody>
          <tr>
          pe="row">1</th>
            <td>Jan Jansen</td>
            <td>Dr. Piet Pietersen</td>
            <td>28-11-2025</td>
            <td>09:30</td>
            <td><span class="badge bg-success">Bevestigd</span></td>
            <td><i class="bi bi-check-circle-fill text-success"></i></td>
            <td>
              <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="#" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
            </td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <td>Maria de Vries</td>
            <td>Dr. Anna Bakker</td>
            <td>29-11-2025</td>
            <td>14:00</td>
            <td><span class="badge bg-success">Bevestigd</span></td>
            <td><i class="bi bi-check-circle-fill text-success"></i></td>
            <td>
              <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="#" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
            </td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <td>Kees Hendriks</td>
            <td>Dr. Piet Pietersen</td>
            <td>30-11-2025</td>
            <td>10:15</td>
            <td><span class="badge bg-danger">Geannuleerd</span></td>
            <td><i class="bi bi-x-circle-fill text-danger"></i></td>
            <td>
              <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="#" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
            </td>
          </tr>
          <tr>
            <th scope="row">4</th>
            <td>Sophie Vermeer</td>
            <td>Dr. Anna Bakker</td>
            <td>01-12-2025</td>
            <td>11:45</td>
            <td><span class="badge bg-success">Bevestigd</span></td>
            <td><i class="bi bi-check-circle-fill text-success"></i></td>
            <td>
              <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="#" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
            </td>
          </tr>
          <tr>
            <th scope="row">5</th>
            <td>Tom van Dijk</td>
            <td>Dr. Piet Pietersen</td>
            <td>02-12-2025</td>
            <td>16:30</td>
            <td><span class="badge bg-success">Bevestigd</span></td>
            <td><i class="bi bi-check-circle-fill text-success"></i></td>
            <td>
              <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
              <a href="#" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  {{-- Mobile cards (small/medium screens) --}}
  <div class="d-lg-none">
    <div class="row g-3">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="card-title mb-0">#1 Jan Jansen</h5>
              <span class="badge bg-success">Bevestigd</span>
            </div>
            <p class="card-text mb-1"><strong>Medewerker:</strong> Dr. Piet Pietersen</p>
            <p class="card-text mb-1"><strong>Datum:</strong> 28-11-2025 om 09:30</p>
            <p class="card-text mb-3"><strong>Actief:</strong> <i class="bi bi-check-circle-fill text-success"></i></p>
            <div class="d-flex gap-2">
              <a href="#" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-eye"></i> Bekijk</a>
              <a href="#" class="btn btn-sm btn-outline-warning flex-fill"><i class="bi bi-pencil"></i> Bewerk</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="card-title mb-0">#2 Maria de Vries</h5>
              <span class="badge bg-success">Bevestigd</span>
            </div>
            <p class="card-text mb-1"><strong>Medewerker:</strong> Dr. Anna Bakker</p>
            <p class="card-text mb-1"><strong>Datum:</strong> 29-11-2025 om 14:00</p>
            <p class="card-text mb-3"><strong>Actief:</strong> <i class="bi bi-check-circle-fill text-success"></i></p>
            <div class="d-flex gap-2">
              <a href="#" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-eye"></i> Bekijk</a>
              <a href="#" class="btn btn-sm btn-outline-warning flex-fill"><i class="bi bi-pencil"></i> Bewerk</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="card-title mb-0">#3 Kees Hendriks</h5>
              <span class="badge bg-danger">Geannuleerd</span>
            </div>
            <p class="card-text mb-1"><strong>Medewerker:</strong> Dr. Piet Pietersen</p>
            <p class="card-text mb-1"><strong>Datum:</strong> 30-11-2025 om 10:15</p>
            <p class="card-text mb-3"><strong>Actief:</strong> <i class="bi bi-x-circle-fill text-danger"></i></p>
            <div class="d-flex gap-2">
              <a href="#" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-eye"></i> Bekijk</a>
              <a href="#" class="btn btn-sm btn-outline-warning flex-fill"><i class="bi bi-pencil"></i> Bewerk</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="card-title mb-0">#4 Sophie Vermeer</h5>
              <span class="badge bg-success">Bevestigd</span>
            </div>
            <p class="card-text mb-1"><strong>Medewerker:</strong> Dr. Anna Bakker</p>
            <p class="card-text mb-1"><strong>Datum:</strong> 01-12-2025 om 11:45</p>
            <p class="card-text mb-3"><strong>Actief:</strong> <i class="bi bi-check-circle-fill text-success"></i></p>
            <div class="d-flex gap-2">
              <a href="#" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-eye"></i> Bekijk</a>
              <a href="#" class="btn btn-sm btn-outline-warning flex-fill"><i class="bi bi-pencil"></i> Bewerk</a>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <h5 class="card-title mb-0">#5 Tom van Dijk</h5>
              <span class="badge bg-success">Bevestigd</span>
            </div>
            <p class="card-text mb-1"><strong>Medewerker:</strong> Dr. Piet Pietersen</p>
            <p class="card-text mb-1"><strong>Datum:</strong> 02-12-2025 om 16:30</p>
            <p class="card-text mb-3"><strong>Actief:</strong> <i class="bi bi-check-circle-fill text-success"></i></p>
            <div class="d-flex gap-2">
              <a href="#" class="btn btn-sm btn-outline-primary flex-fill"><i class="bi bi-eye"></i> Bekijk</a>
              <a href="#" class="btn btn-sm btn-outline-warning flex-fill"><i class="bi bi-pencil"></i> Bewerk</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection