@extends('layout.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 m-0">Overzicht afspraken</h1>
    <a href="{{ url('/afspraken/create') }}" class="btn btn-primary btn-sm">
      <i class="bi bi-plus-circle"></i> Nieuwe afspraak
    </a>
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th scope="col">#</th>

          <th scope="col">Datum</th>
          <th scope="col">Tijd</th>
          <th scope="col">Status</th>
          <th scope="col">Actief</th>
          <th scope="col">Acties</th>
        </tr>
      </thead>
      <tbody>

        <tr>
          <th scope="row"></th>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection