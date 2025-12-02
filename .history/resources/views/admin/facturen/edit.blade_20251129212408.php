@extends('layout.app')

@section('title', 'Factuur Bewerken')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Factuur Bewerken</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.facturen.update', $factuur->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Read-only Patient and Behandeling Info -->
                        <div class="mb-3">
                            <label class="form-label">Patiënt</label>
                            <input type="text" class="form-control" 
                                   value="{{ $factuur->patient->persoon->voornaam }} {{ $factuur->patient->persoon->tussenvoegsel }} {{ $factuur->patient->persoon->achternaam }} ({{ $factuur->patient->nummer }})" 
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Behandeling</label>
                            <input type="text" class="form-control" 
                                   value="{{ $factuur->behandeling->behandelingtype }} - {{ \Carbon\Carbon::parse($factuur->behandeling->datum)->format('d-m-Y') }}" 
                                   readonly>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="nummer" class="form-label">Factuurnummer <span class="text-danger">*</span></label>
                            <input type="text" name="nummer" id="nummer" class="form-control @error('nummer') is-invalid @enderror" 
                                   value="{{ old('nummer', $factuur->nummer) }}" required>
                            @error('nummer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="datum" class="form-label">Factuurdatum <span class="text-danger">*</span></label>
                            <input type="date" name="datum" id="datum" class="form-control @error('datum') is-invalid @enderror" 
                                   value="{{ old('datum', $factuur->datum->format('Y-m-d')) }}" required>
                            @error('datum')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bedrag" class="form-label">Bedrag (&euro;) <span class="text-danger">*</span></label>
                            <input type="number" name="bedrag" id="bedrag" class="form-control @error('bedrag') is-invalid @enderror" 
                                   value="{{ old('bedrag', $factuur->bedrag) }}" step="0.01" min="0" required>
                            @error('bedrag')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="Niet-Verzonden" {{ old('status', $factuur->status) == 'Niet-Verzonden' ? 'selected' : '' }}>Niet-Verzonden</option>
                                <option value="Verzonden" {{ old('status', $factuur->status) == 'Verzonden' ? 'selected' : '' }}>Verzonden</option>
                                <option value="Onbetaald" {{ old('status', $factuur->status) == 'Onbetaald' ? 'selected' : '' }}>Onbetaald</option>
                                <option value="Betaald" {{ old('status', $factuur->status) == 'Betaald' ? 'selected' : '' }}>Betaald</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="opmerking" class="form-label">Opmerking</label>
                            <textarea name="opmerking" id="opmerking" class="form-control @error('opmerking') is-invalid @enderror" rows="3">{{ old('opmerking', $factuur->opmerking) }}</textarea>
                            @error('opmerking')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.facturen.index') }}" class="btn btn-secondary">Annuleren</a>
                            <button type="submit" class="btn btn-primary">Factuur Bijwerken</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
