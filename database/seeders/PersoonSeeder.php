<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersoonSeeder extends Seeder
{
    public function run(): void
    {
        $personen = [
            ['voornaam' => 'Jan', 'tussenvoegsel' => 'de', 'achternaam' => 'Vries', 'geboortedatum' => '1985-03-15'],
            ['voornaam' => 'Sarah', 'tussenvoegsel' => null, 'achternaam' => 'Bakker', 'geboortedatum' => '1992-07-22'],
            ['voornaam' => 'Mohammed', 'tussenvoegsel' => null, 'achternaam' => 'Ali', 'geboortedatum' => '1978-11-08'],
            ['voornaam' => 'Emma', 'tussenvoegsel' => null, 'achternaam' => 'Peters', 'geboortedatum' => '1995-05-12'],
            ['voornaam' => 'Lucas', 'tussenvoegsel' => 'van', 'achternaam' => 'Dam', 'geboortedatum' => '1988-09-30'],
            ['voornaam' => 'Sophie', 'tussenvoegsel' => null, 'achternaam' => 'Jansen', 'geboortedatum' => '1990-02-14'],
            ['voornaam' => 'David', 'tussenvoegsel' => 'van der', 'achternaam' => 'Berg', 'geboortedatum' => '1982-06-25'],
        ];

        foreach ($personen as $persoon) {
            DB::table('persoon')->insert([
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
        }

        $this->command->info(count($personen) . ' personen aangemaakt!');
    }
}