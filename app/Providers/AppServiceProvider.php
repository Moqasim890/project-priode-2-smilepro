<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

use App\Models\PatientModel as Patient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * View Composer:
         * Zorgt ervoor dat data automatisch beschikbaar is in views, 
         * ook in bestanden die geen controller hebben.
         * 
         * In dit geval is de data beschikbaar in alle bestanden in de 'layout' map.
         * 
         * Alle stappen:
         * 1. Check of de gebruiker is ingelogd.
         * 2. Pak de id van de ingelogde gebruiker.
         * 3. Gebruik deze id in een stored procedure om het aantal berichten te halen.
         * 4. Zet het resultaat om naar string zodat Blade het correct kan renderen.
        */
        View::composer('layout.*', function ($view) {
            if (Auth::check()) {
                $id = Auth::id();
                $result = Patient::SP_CountBerichtenById($id);
                $aantalberichten = (string) $result[0]->AantalBerichten;
            } else {
                $aantalberichten = "0";
            }
            
            $view->with('aantalberichten', $aantalberichten);
        });
    }
}
