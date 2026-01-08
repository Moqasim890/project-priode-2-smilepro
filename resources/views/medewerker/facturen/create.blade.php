@extends('layout.app')

@section('title', 'Nieuwe Factuur')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Nieuwe Factuur Aanmaken</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('medewerker.factuur.store') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="patientid" class="form-label">Patiënt <span class="text-danger">*</span></label>
                            <select name="patientid" id="patientid" class="form-select @error('patientid') is-invalid @enderror" required>
                                <option value="">-- Selecteer Patiënt --</option>
                                @foreach($patienten as $patient)
                                    <option value="{{ $patient-> persoonid }}" {{ old('patientid') == $patient->persoonid ? 'selected' : '' }}>
                                        {{ $patient->username }} ({{ $patient->nummer }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patientid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="behandelingid" class="form-label">Behandeling <span class="text-danger">*</span></label>
                            <select name="behandelingid[]" id="behandelingid" class="form-select @error('behandelingid') is-invalid @enderror" required disabled multiple>
                                <option value="">-- Selecteer eerst een patiënt --</option>
                            </select>
                            @error('behandelingid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Alleen behandelingen zonder factuur worden getoond</div>
                        </div>

                        <div class="mb-3">
                            <label for="nummer" class="form-label">Factuurnummer <span class="text-danger">*</span></label>
                            <input type="text" name="nummer" id="nummer" class="form-control @error('nummer') is-invalid @enderror"
                                   value="{{ old('nummer', 'F' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) }}" readonly required>
                            @error('nummer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="datum" class="form-label">Factuurdatum <span class="text-danger">*</span></label>
                            <input type="date" name="datum" id="datum" class="form-control @error('datum') is-invalid @enderror"
                                   value="{{ old('datum', date('Y-m-d')) }}" required>
                            @error('datum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bedrag" class="form-label">Bedrag (&euro;) <span class="text-danger">*</span></label>
                            <input type="number" name="bedrag" id="bedrag" class="form-control @error('bedrag') is-invalid @enderror"
                                   value="{{ old('bedrag') }}" step="0.01" min="0" readonly required>
                            @error('bedrag')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" readonly required>
                                <option value="Niet-Verzonden" {{ old('status') == 'Niet-Verzonden' ? 'selected' : '' }}>Niet-Verzonden</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('medewerker.factuur.index') }}" class="btn btn-secondary">Annuleren</a>
                            <button type="submit" class="btn btn-primary">Factuur Aanmaken</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const patientSelect = document.getElementById('patientid');
    const behandelingSelect = document.getElementById('behandelingid');
    const bedragInput = document.getElementById('bedrag');

    patientSelect.addEventListener('change', function() {
        const patientId = this.value;

        if (!patientId) {
            behandelingSelect.disabled = true;
            behandelingSelect.innerHTML = '<option value="">-- Selecteer eerst een patiënt --</option>';
            return;
        }

        // Fetch behandelingen for selected patient
        fetch(`/medewerker/factuur/behandelingen/${patientId}`)
            .then(response => response.json())
            .then(data => {
                behandelingSelect.innerHTML = '<option value="">-- aan het laden --</option>';
                behandelingSelect.disabled = true;

                console.log(data);
                if (data.length === 0) {
                    console.log('here');
                    setTimeout(() => {
                        document.getElementById('behandelingid').innerHTML = '<option value="">Geen beschikbare behandelingen</option>';
                    document.getElementById('behandelingid').disabled = true;
                    }, 1000);
                } else {
                    behandelingSelect.innerHTML = '<option value="">-- Selecteer een behandeling --</option>'
                    behandelingSelect.disabled = false;
                    data.forEach(behandeling => {
                        const datum = new Date(behandeling.datum).toLocaleDateString('nl-NL');
                        const option = document.createElement('option');
                        option.value = behandeling.id;
                        option.textContent = `${behandeling.behandelingtype} - ${datum} (€${parseFloat(behandeling.kosten).toFixed(2)})`;
                        option.dataset.kosten = behandeling.kosten;
                        behandelingSelect.appendChild(option);
                    });
                    behandelingSelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                behandelingSelect.innerHTML = '<option value="">Fout bij laden behandelingen</option>';
            });
    });

    behandelingSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.dataset.kosten) {
            bedragInput.value = parseFloat(selectedOption.dataset.kosten).toFixed(2);
        }
    });
});
</script>
@endsection
