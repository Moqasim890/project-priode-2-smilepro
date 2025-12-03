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
  <div class="d-none d-lg-block">
    @if($afspraken->count() > 0)
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
            @foreach($afspraken as $afspraak)
            <tr>
              <th scope="row">{{ $afspraak->id }}</th>
              <td>
                @php
                  $patientNamen = [
                    1 => 'Jan Jansen',
                    2 => 'Maria de Vries',
                    3 => 'Kees Hendriks',
                    4 => 'Sophie Vermeer',
                    5 => 'Tom van Dijk',
                  ];
                @endphp
                {{ $patientNamen[$afspraak->patientid] ?? 'Patient #' . $afspraak->patientid }}
              </td>
              <td>
                @php
                  $medewerkerNamen = [
                    1 => 'Dr. Piet Pietersen',
                    2 => 'Dr. Anna Bakker',
                  ];
                @endphp
                {{ $medewerkerNamen[$afspraak->medewerkerid] ?? 'Medewerker #' . $afspraak->medewerkerid }}
              </td>
              <td>{{ \Carbon\Carbon::parse($afspraak->datum)->format('d-m-Y') }}</td>
              <td>{{ \Carbon\Carbon::parse($afspraak->tijd)->format('H:i') }}</td>
              <td>
                <span class="badge {{ $afspraak->status === 'Bevestigd' ? 'bg-success' : 'bg-danger' }}">
                  {{ $afspraak->status }}
                </span>
              </td>
              <td>
                @if($afspraak->isactief)
                  <i class="bi bi-check-circle-fill text-success"></i>
                @else
                  <i class="bi bi-x-circle-fill text-danger"></i>
                @endif
              </td>
              <td>
                <div class="btn-group btn-group-sm" role="group">
                  <a href="{{ url('/afspraken/' . $afspraak->id) }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="{{ url('/afspraken/' . $afspraak->id . '/edit') }}" class="btn btn-outline-warning">
                    <i class="bi bi-pencil"></i>
                  </a>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      <div class="mt-3">
        {{ $afspraken->links() }}
      </div>
    @else
      <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Er zijn geen afspraken gevonden.
      </div>
    @endif
  </div>

  {{-- Mobile cards (small/medium screens) --}}
  <div class="d-lg-none">
    @if($afspraken->count() > 0)
      <div class="row g-3">
        @foreach($afspraken as $afspraak)
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title mb-0">
                  #{{ $afspraak->id }}
                  @php
                    $patientNamen = [
                      1 => 'Jan Jansen',
                      2 => 'Maria de Vries',
                      3 => 'Kees Hendriks',
                      4 => 'Sophie Vermeer',
                      5 => 'Tom van Dijk',
                    ];
                  @endphp
                  {{ $patientNamen[$afspraak->patientid] ?? 'Patient #' . $afspraak->patientid }}
                </h5>
                <span class="badge {{ $afspraak->status === 'Bevestigd' ? 'bg-success' : 'bg-danger' }}">
                  {{ $afspraak->status }}
                </span>
              </div>
              <p class="card-text mb-1">
                <strong>Medewerker:</strong>
                @php
                  $medewerkerNamen = [
                    1 => 'Dr. Piet Pietersen',
                    2 => 'Dr. Anna Bakker',
                  ];
                @endphp
                {{ $medewerkerNamen[$afspraak->medewerkerid] ?? 'Medewerker #' . $afspraak->medewerkerid }}
              </p>
              <p class="card-text mb-1">
                <strong>Datum:</strong> {{ \Carbon\Carbon::parse($afspraak->datum)->format('d-m-Y') }} om {{ \Carbon\Carbon::parse($afspraak->tijd)->format('H:i') }}
              </p>
              <p class="card-text mb-3">
                <strong>Actief:</strong>
                @if($afspraak->isactief)
                  <i class="bi bi-check-circle-fill text-success"></i>
                @else
                  <i class="bi bi-x-circle-fill text-danger"></i>
                @endif
              </p>
              <div class="d-flex gap-2">
                <a href="{{ url('/afspraken/' . $afspraak->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                  <i class="bi bi-eye"></i> Bekijk
                </a>
                <a href="{{ url('/afspraken/' . $afspraak->id . '/edit') }}" class="btn btn-sm btn-outline-warning flex-fill">
                  <i class="bi bi-pencil"></i> Bewerk
                </a>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      {{-- Pagination --}}
      <div class="mt-3">
        {{ $afspraken->links() }}
      </div>
    @else
      <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Er zijn geen afspraken gevonden.
      </div>
    @endif
  </div>
</div>
@endsection