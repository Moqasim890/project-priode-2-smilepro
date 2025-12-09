<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedewerkerSeeder extends Seeder
{
    public function run(): void
    {
        // Maak eerst personen aan voor medewerkers
        $medewerkerPersonen = [
            ['voornaam' => 'Dr. Jan', 'tussenvoegsel' => null, 'achternaam' => 'Tandarts', 'geboortedatum' => '1975-05-20'],
            ['voornaam' => 'Lisa', 'tussenvoegsel' => null, 'achternaam' => 'Hygienist', 'geboortedatum' => '1988-03-15'],
        ];

        $persoonIds = [];
        foreach ($medewerkerPersonen as $persoon) {
            $id = DB::table('persoon')->insertGetId([
                'gebruikerid' => null,
                'voornaam' => $persoon['voornaam'],
                'tussenvoegsel' => $persoon['tussenvoegsel'],
                'achternaam' => $persoon['achternaam'],
                'geboortedatum' => $persoon['geboortedatum'],
                'isactief' => 1,
                'opmerking' => null,
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ]);
            $persoonIds[] = $id;
        }

        // Maak medewerkers aan
        $medewerkers = [
            ['persoonid' => $persoonIds[0], 'nummer' => 'M00001', 'medewerkertype' => 'Tandarts', 'specialisatie' => 'Algemeen'],
            ['persoonid' => $persoonIds[1], 'nummer' => 'M00002', 'medewerkertype' => 'Mondhygiënist', 'specialisatie' => 'Preventie'],
        ];

        foreach ($medewerkers as $medewerker) {
            DB::table('medewerker')->insert([
                'persoonid' => $medewerker['persoonid'],
                'nummer' => $medewerker['nummer'],
                'medewerkertype' => $medewerker['medewerkertype'],
                'specialisatie' => $medewerker['specialisatie'],
                'beschikbaarheid' => null,
                'isactief' => 1,
                'opmerking' => null,
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ]);
        }

        $this->command->info(count($medewerkers) . ' medewerkers aangemaakt!');
    }
}