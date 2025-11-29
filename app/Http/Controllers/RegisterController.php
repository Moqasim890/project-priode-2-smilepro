<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'voornaam' => ['required', 'string', 'max:100'],
            'achternaam' => ['required', 'string', 'max:100'],
            'tussenvoegsel' => ['nullable', 'string', 'max:20'],
            'geboortedatum' => ['required', 'date', 'before:today'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create linked person row using validated fields
        Person::create([
            'gebruikerid' => $user->id,
            'voornaam' => $validated['voornaam'],
            'tussenvoegsel' => $validated['tussenvoegsel'] ?? null,
            'achternaam' => $validated['achternaam'],
            'geboortedatum' => $validated['geboortedatum'],
            'isactief' => 1,
            'opmerking' => null,
        ]);

        // Assign default role (klant)
        $user->assignRole('klant');

        Auth::login($user);

        return redirect('/')->with('success', 'Account succesvol aangemaakt!');
    }
}
