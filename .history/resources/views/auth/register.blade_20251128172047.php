@extends('layout.app')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h4 fw-bold text-center mb-4">Registreren</h2>
                    @if ($errors->any())
                        <div class="alert alert-danger small">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('register') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus class="form-control @error('name') is-invalid @enderror">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mailadres</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Wachtwoord</label>
                            <input type="password" name="password" id="password" required class="form-control @error('password') is-invalid @enderror">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Bevestig wachtwoord</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required class="form-control">
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-person-plus-fill me-1"></i>Registreren</button>
                            <a href="{{ route('login') }}" class="small">Al een account?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
