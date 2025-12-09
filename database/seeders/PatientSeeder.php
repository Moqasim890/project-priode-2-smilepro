<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $persoonIds = DB::table('persoon')->pluck('id')->toArray();

        if (empty($persoonIds)) {
            $this->command->warn('Geen personen gevonden. Eerst PersoonSeeder runnen.');
            return;
        }

        foreach ($persoonIds as $index => $persoonId) {
            DB::table('patient')->insert([
                'persoonid' => $persoonId,
                'nummer' => 'P' . str_pad($index + 1, 5, '0', STR_PAD_LEFT),
                'medischdossier' => 'Standaard dossier',
                'isactief' => 1,
                'opmerking' => null,
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ]);
        }

        $this->command->info(count($persoonIds) . ' patiënten aangemaakt!');
    }
}