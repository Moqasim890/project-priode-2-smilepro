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
                    <form action="{{ route('admin.facturen.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="patientid" class="form-label">Patiënt <span class="text-danger">*</span></label>
                            <select name="patientid" id="patientid" class="form-select @error('patientid') is-invalid @enderror" required>
                                <option value="">-- Selecteer Patiënt --</option>
                                @foreach($patienten as $patient)
                                    <option value="{{ $patient->id }}" {{ old('patientid') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->naam }} ({{ $patient->nummer }})
                                    </option>
                                @endforeach
                            </select>
                            @error('patientid')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="behandelingid" class="form-label">Behandeling <span class="text-danger">*</span></label>
                            <select name="behandelingid" id="behandelingid" class="form-select @error('behandelingid') is-invalid @enderror" required disabled>
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
                                   value="{{ old('nummer', 'F' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) }}" required>
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
                                   value="{{ old('bedrag') }}" step="0.01" min="0" required>
                            @error('bedrag')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Niet-Verzonden" {{ old('status') == 'Niet-Verzonden' ? 'selected' : '' }}>Niet-Verzonden</option>
                                <option value="Verzonden" {{ old('status') == 'Verzonden' ? 'selected' : '' }}>Verzonden</option>
                                <option value="Onbetaald" {{ old('status') == 'Onbetaald' ? 'selected' : '' }}>Onbetaald</option>
                                <option value="Betaald" {{ old('status') == 'Betaald' ? 'selected' : '' }}>Betaald</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="opmerking" class="form-label">Opmerking</label>
                            <textarea name="opmerking" id="opmerking" class="form-control @error('opmerking') is-invalid @enderror" rows="3">{{ old('opmerking') }}</textarea>
                            @error('opmerking')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.facturen.index') }}" class="btn btn-secondary">Annuleren</a>
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
        fetch(`/admin/facturen/behandelingen/${patientId}`)
            .then(response => response.json())
            .then(data => {
                behandelingSelect.innerHTML = '<option value="">-- Selecteer Behandeling --</option>';
                
                if (data.length === 0) {
                    behandelingSelect.innerHTML += '<option value="">Geen beschikbare behandelingen</option>';
                    behandelingSelect.disabled = true;
                } else {
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
