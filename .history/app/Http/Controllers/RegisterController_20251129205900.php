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
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Create linked person row for the user in `persoon`
        // Split name into first and last rudimentarily
        $parts = preg_split('/\s+/', $validated['name'], -1, PREG_SPLIT_NO_EMPTY);
        $voornaam = $parts[0] ?? $validated['name'];
        $achternaam = $parts[1] ?? '';

        Person::create([
            'gebruikerid' => $user->id,
            'voornaam' => $voornaam,
            'tussenvoegsel' => null,
            'achternaam' => $achternaam,
            // NOTE: geboortedatum is NOT NULL in SQL; use a sensible default
            'geboortedatum' => '2000-01-01',
            'isactief' => 1,
            'opmerking' => null,
        ]);

        // Assign default role (klant)
        $user->assignRole('klant');

        Auth::login($user);

        return redirect('/')->with('success', 'Account succesvol aangemaakt!');
    }
}
