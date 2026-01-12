@extends('layout.app')

@section('title', 'Nieuwe Factuur')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-receipt me-2"></i>Nieuwe Factuur Aanmaken</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('medewerker.factuur.store') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="patientid" class="form-label fw-bold">Patiënt <span class="text-danger">*</span></label>
                                <select name="patientid" id="patientid" class="form-select @error('patientid') is-invalid @enderror" required>
                                    <option value="">-- Selecteer Patiënt --</option>
                                    @foreach($patienten as $patient)
                                        <option value="{{ $patient->persoonid }}" {{ old('patientid') == $patient->persoonid ? 'selected' : '' }}>
                                            {{ $patient->username }} ({{ $patient->nummer }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('patientid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nummer" class="form-label fw-bold">Factuurnummer <span class="text-danger">*</span></label>
                                <input type="text" name="nummer" id="nummer" class="form-control @error('nummer') is-invalid @enderror"
                                       value="{{ old('nummer', 'F' . date('Y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT)) }}" readonly required>
                                @error('nummer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Behandelingen <span class="text-danger">*</span></label>
                            <div class="form-text mb-2">
                                <i class="bi bi-info-circle me-1"></i>Selecteer één of meerdere behandelingen (alleen behandelingen zonder factuur)
                            </div>

                            <div id="behandelingen-container" class="border rounded p-3 bg-light" style="min-height: 120px; max-height: 400px; overflow-y: auto;">
                                <div class="text-muted text-center py-4">
                                    <i class="bi bi-arrow-up-circle me-2"></i>Selecteer eerst een patiënt om behandelingen te laden
                                </div>
                            </div>

                            @error('behandelingid')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror

                            <!-- Selected count badge -->
                            <div class="mt-2">
                                <span id="selected-count" class="badge bg-secondary">0 behandelingen geselecteerd</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="datum" class="form-label fw-bold">Factuurdatum <span class="text-danger">*</span></label>
                                <input type="date" name="datum" id="datum" class="form-control @error('datum') is-invalid @enderror"
                                       value="{{ old('datum', date('Y-m-d')) }}" required>
                                @error('datum')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="bedrag" class="form-label fw-bold">Totaalbedrag (&euro;) <span class="text-danger">*</span></label>
                                <input type="number" name="bedrag" id="bedrag" class="form-control @error('bedrag') is-invalid @enderror"
                                       value="{{ old('bedrag', '0.00') }}" step="0.01" min="0" readonly required>
                                @error('bedrag')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Automatisch berekend o.b.v. geselecteerde behandelingen</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="Niet-Verzonden" {{ old('status', 'Niet-Verzonden') == 'Niet-Verzonden' ? 'selected' : '' }}>Niet-Verzonden</option>
                                    <option value="Verzonden" {{ old('status') == 'Verzonden' ? 'selected' : '' }}>Verzonden</option>
                                    <option value="Betaald" {{ old('status') == 'Betaald' ? 'selected' : '' }}>Betaald</option>
                                    <option value="Onbetaald" {{ old('status') == 'Onbetaald' ? 'selected' : '' }}>Onbetaald</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('medewerker.factuur.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle me-1"></i>Annuleren
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="submit-btn" disabled>
                                <i class="bi bi-check-circle me-1"></i>Factuur Aanmaken
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.behandeling-item {
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 12px 15px;
    margin-bottom: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
}

.behandeling-item:hover {
    border-color: #0d6efd;
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
    transform: translateY(-1px);
}

.behandeling-item.selected {
    border-color: #0d6efd;
    background: linear-gradient(135deg, #e7f1ff 0%, #f0f7ff 100%);
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.25);
}

.behandeling-item input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.behandeling-details {
    flex-grow: 1;
    margin-left: 12px;
}

.behandeling-type {
    font-weight: 600;
    color: #212529;
    font-size: 1.05rem;
}

.behandeling-info {
    color: #6c757d;
    font-size: 0.9rem;
}

.behandeling-prijs {
    font-weight: 700;
    color: #198754;
    font-size: 1.1rem;
    white-space: nowrap;
}

.loading-spinner {
    text-align: center;
    padding: 40px;
    color: #6c757d;
}

.loading-spinner .spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const patientSelect = document.getElementById('patientid');
    const behandelingenContainer = document.getElementById('behandelingen-container');
    const bedragInput = document.getElementById('bedrag');
    const selectedCountBadge = document.getElementById('selected-count');
    const submitBtn = document.getElementById('submit-btn');

    let selectedBehandelingen = new Set();

    patientSelect.addEventListener('change', function() {
        const patientId = this.value;
        selectedBehandelingen.clear();

        if (!patientId) {
            behandelingenContainer.innerHTML = `
                <div class="text-muted text-center py-4">
                    <i class="bi bi-arrow-up-circle me-2"></i>Selecteer eerst een patiënt om behandelingen te laden
                </div>`;
            updateTotals();
            return;
        }

        // Show loading spinner
        behandelingenContainer.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Laden...</span>
                </div>
                <p class="mt-3 mb-0">Behandelingen ophalen...</p>
            </div>`;

        // Fetch behandelingen for selected patient
        fetch(`/medewerker/factuur/behandelingen/${patientId}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    behandelingenContainer.innerHTML = `
                        <div class="text-center py-4 text-warning">
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                            <p class="mt-3 mb-0 fw-bold">Geen beschikbare behandelingen</p>
                            <p class="text-muted small">Deze patiënt heeft geen onbefactureerde behandelingen</p>
                        </div>`;
                    updateTotals();
                } else {
                    renderBehandelingen(data);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                behandelingenContainer.innerHTML = `
                    <div class="text-center py-4 text-danger">
                        <i class="bi bi-exclamation-circle" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-0 fw-bold">Fout bij laden behandelingen</p>
                        <p class="text-muted small">Probeer het opnieuw of neem contact op met de beheerder</p>
                    </div>`;
            });
    });

    function renderBehandelingen(behandelingen) {
        behandelingenContainer.innerHTML = '';

        behandelingen.forEach(behandeling => {
            const datum = new Date(behandeling.datum).toLocaleDateString('nl-NL', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            const tijd = behandeling.tijd ? behandeling.tijd.substring(0, 5) : '';

            const itemDiv = document.createElement('div');
            itemDiv.className = 'behandeling-item d-flex align-items-center';
            itemDiv.dataset.id = behandeling.id;
            itemDiv.dataset.kosten = behandeling.kosten;

            itemDiv.innerHTML = `
                <input type="checkbox"
                       class="form-check-input behandeling-checkbox"
                       name="behandelingid[]"
                       value="${behandeling.id}"
                       id="behandeling-${behandeling.id}">
                <div class="behandeling-details">
                    <div class="behandeling-type">
                        <i class="bi bi-bandaid me-1"></i>${behandeling.behandelingtype}
                    </div>
                    <div class="behandeling-info">
                        <i class="bi bi-calendar3 me-1"></i>${datum}
                        ${tijd ? `<i class="bi bi-clock ms-2 me-1"></i>${tijd}` : ''}
                        ${behandeling.omschrijving ? `<span class="ms-2">- ${behandeling.omschrijving}</span>` : ''}
                    </div>
                </div>
                <div class="behandeling-prijs">
                    €${parseFloat(behandeling.kosten).toFixed(2)}
                </div>
            `;

            // Click handler for the entire div
            itemDiv.addEventListener('click', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = this.querySelector('.behandeling-checkbox');
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });

            // Checkbox change handler
            const checkbox = itemDiv.querySelector('.behandeling-checkbox');
            checkbox.addEventListener('change', function(e) {
                e.stopPropagation();

                if (this.checked) {
                    selectedBehandelingen.add({
                        id: behandeling.id,
                        kosten: parseFloat(behandeling.kosten)
                    });
                    itemDiv.classList.add('selected');
                } else {
                    selectedBehandelingen.forEach(item => {
                        if (item.id == behandeling.id) {
                            selectedBehandelingen.delete(item);
                        }
                    });
                    itemDiv.classList.remove('selected');
                }

                updateTotals();
            });

            behandelingenContainer.appendChild(itemDiv);
        });
    }

    function updateTotals() {
        const count = selectedBehandelingen.size;
        let totaalBedrag = 0;

        selectedBehandelingen.forEach(item => {
            totaalBedrag += item.kosten;
        });

        bedragInput.value = totaalBedrag.toFixed(2);
        selectedCountBadge.textContent = `${count} behandeling${count !== 1 ? 'en' : ''} geselecteerd`;

        // Update badge color
        if (count === 0) {
            selectedCountBadge.className = 'badge bg-secondary';
            submitBtn.disabled = true;
        } else {
            selectedCountBadge.className = 'badge bg-success';
            submitBtn.disabled = false;
        }
    }
});
</script>
@endsection
