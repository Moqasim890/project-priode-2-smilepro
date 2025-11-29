<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     */
    public function show()
    {
        return view('profile.show', [
            'user' => auth()->user()
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Person fields optional but can be set
            'voornaam' => ['nullable', 'string', 'max:100'],
            'tussenvoegsel' => ['nullable', 'string', 'max:20'],
            'achternaam' => ['nullable', 'string', 'max:100'],
            'geboortedatum' => ['nullable', 'date'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update or create linked person row
        if (array_key_exists('voornaam', $validated) || array_key_exists('achternaam', $validated) || array_key_exists('geboortedatum', $validated) || array_key_exists('tussenvoegsel', $validated)) {
            $personData = [
                'voornaam' => $validated['voornaam'] ?? null,
                'tussenvoegsel' => $validated['tussenvoegsel'] ?? null,
                'achternaam' => $validated['achternaam'] ?? null,
                // If provided, use date; else keep existing or default
                'geboortedatum' => $validated['geboortedatum'] ?? (optional($user->person)->geboortedatum ?? '2000-01-01'),
                'isactief' => 1,
            ];

            $user->person()->updateOrCreate(['gebruikerid' => $user->id], $personData);
        }

        return redirect()->route('profile')->with('success', 'Profiel succesvol bijgewerkt!');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'Het huidige wachtwoord is onjuist.'
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return redirect()->route('profile')->with('success', 'Wachtwoord succesvol gewijzigd!');
    }
}
