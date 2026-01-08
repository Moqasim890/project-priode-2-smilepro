<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();
            
            if ($user->hasRole('Praktijkmanagement')) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->hasAnyRole(['Tandarts', 'Mondhygiënist', 'Assistent'])) {
                return redirect()->intended(route('medewerker.dashboard'));
            } elseif ($user->hasRole('Patiënt')) {
                return redirect()->intended(route('Patient.berichten.index'));
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'email/wachtwoord is incorrect.',
            

        ])->onlyInput('email');
    }
}
