{{--
    Medewerkers Overzicht View
    Doel: Overzicht van alle medewerkers (gebaseerd op gebruikers + rollen)
    Toegang: Alleen Praktijkmanagement rol

    Happy scenario:
        /management/medewerkers

    Unhappy scenario:
        /management/medewerkers?forceError=1
--}}
@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold">
            <i class="bi bi-people-fill me-2"></i>Overzicht medewerkers
        </h1>
        <span class="badge bg-secondary-subtle text-secondary border">
            Praktijkmanagement · Medewerkers
        </span>
    </div>

    @if(!empty($errorMessage))
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>{{ $errorMessage }}</div>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light d-flex flex-wrap gap-3 justify-content-between align-items-center">
            <div>
                <h2 class="h5 mb-0">Medewerkers</h2>
                <small class="text-muted">
                    Snel overzicht van wie er in de praktijk werkt, inclusief functie en contactgegevens.
                </small>
            </div>
            <form method="GET" class="d-flex gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ $search ?? '' }}"
                    class="form-control form-control-sm"
                    placeholder="Zoek op naam of e-mail..."
                >
                <select
                    name="per_page"
                    class="form-select form-select-sm"
                    onchange="this.form.submit()"
                    style="max-width: 120px;"
                >
                    @foreach([5,10,25,50,100] as $size)
                        <option value="{{ $size }}" {{ (request('per_page', 10) == $size) ? 'selected' : '' }}>
                            {{ $size }} / pagina
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Naam</th>
                            <th scope="col">Functie</th>
                            <th scope="col">Nummer</th>
                            <th scope="col">E-mail</th>
                            <th scope="col" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medewerkers as $medewerker)
                            @php
                                // Primaire rol bepalen (iets anders dan 'Patiënt' als dat kan)
                                $primaryRole = 'Onbekend';
                                foreach ($medewerker->roles as $role) {
                                    if ($role->name !== 'Patiënt') {
                                        $primaryRole = $role->name;
                                        break;
                                    }
                                }

                                // Simpel “nummer” gebaseerd op user ID
                                $nummer = 'U-' . str_pad($medewerker->id, 4, '0', STR_PAD_LEFT);
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $medewerker->name }}</div>
                                </td>
                                <td>
                                    <span class="badge border bg-white text-dark">
                                        {{ $primaryRole }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $nummer }}</span>
                                </td>
                                <td>
                                    @if($medewerker->email)
                                        <a href="mailto:{{ $medewerker->email }}">{{ $medewerker->email }}</a>
                                    @else
                                        <span class="text-muted">Geen e-mail bekend</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge border bg-white text-dark">
                                        Actief
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Er zijn momenteel geen medewerkers om weer te geven.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-light d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                @php
                    $total = method_exists($medewerkers, 'total') ? $medewerkers->total() : $medewerkers->count();
                @endphp
                <small class="text-muted">
                    Totaal: {{ $total }} medewerker{{ $total === 1 ? '' : 's' }}
                </small>
            </div>
            <div>
                @if(method_exists($medewerkers, 'links'))
                    {{ $medewerkers->onEachSide(1)->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection