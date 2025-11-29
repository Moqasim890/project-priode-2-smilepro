<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FactuurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, create some patients
        $personen = DB::table('persoon')->limit(3)->get();
        
        $patients = [];
        foreach ($personen as $persoon) {
            $patientId = DB::table('patient')->insertGetId([
                'persoonid' => $persoon->id,
                'nummer' => 'P' . str_pad($persoon->id, 5, '0', STR_PAD_LEFT),
                'medischdossier' => 'Algemene controles en preventief onderhoud',
                'isactief' => 1,
                'opmerking' => null,
                'datumaangemaakt' => Carbon::now(),
                'datumgewijzigd' => Carbon::now(),
            ]);
            $patients[] = $patientId;
        }

        // Create some medewerkers (tandartsen)
        $medewerkers = [];
        foreach ($personen as $index => $persoon) {
            if ($index < 2) { // Create 2 medewerkers
                $medewerkerId = DB::table('medewerker')->insertGetId([
                    'persoonid' => $persoon->id,
                    'nummer' => 'M' . str_pad($persoon->id, 5, '0', STR_PAD_LEFT),
                    'medewerkertype' => $index == 0 ? 'Tandarts' : 'Mondhygiënist',
                    'specialisatie' => $index == 0 ? 'Algemene tandheelkunde' : 'Preventieve zorg',
                    'beschikbaarheid' => 'Ma-Vr 09:00-17:00',
                    'isactief' => 1,
                    'opmerking' => null,
                    'datumaangemaakt' => Carbon::now(),
                    'datumgewijzigd' => Carbon::now(),
                ]);
                $medewerkers[] = $medewerkerId;
            }
        }

        // Create behandelingen for each patient
        $behandelingTypes = ['Controles', 'Vullingen', 'Gebitsreiniging', 'Orthodontie', 'Wortelkanaalbehandelingen'];
        $kosten = [75.00, 150.00, 85.00, 1200.00, 450.00];
        $statuses = ['Behandeld', 'Behandeld', 'Onbehandeld'];

        $behandelingen = [];
        foreach ($patients as $patientId) {
            for ($i = 0; $i < 3; $i++) {
                $typeIndex = array_rand($behandelingTypes);
                $behandelingId = DB::table('behandeling')->insertGetId([
                    'medewerkerid' => $medewerkers[array_rand($medewerkers)],
                    'patientid' => $patientId,
                    'datum' => Carbon::now()->subDays(rand(1, 60))->format('Y-m-d'),
                    'tijd' => sprintf('%02d:%02d:00', rand(9, 16), [0, 15, 30, 45][rand(0, 3)]),
                    'behandelingtype' => $behandelingTypes[$typeIndex],
                    'omschrijving' => 'Standaard ' . strtolower($behandelingTypes[$typeIndex]) . ' behandeling',
                    'kosten' => $kosten[$typeIndex],
                    'status' => $statuses[array_rand($statuses)],
                    'isactief' => 1,
                    'opmerking' => null,
                    'datumaangemaakt' => Carbon::now(),
                    'datumgewijzigd' => Carbon::now(),
                ]);
                $behandelingen[] = ['id' => $behandelingId, 'patientid' => $patientId, 'kosten' => $kosten[$typeIndex]];
            }
        }

        // Create facturen for behandelde behandelingen
        $factuurStatuses = ['Verzonden', 'Niet-Verzonden', 'Betaald', 'Onbetaald'];
        $factuurCounter = 1;
        
        foreach ($behandelingen as $behandeling) {
            // Create factuur for 70% of behandelingen
            if (rand(1, 10) <= 7) {
                DB::table('factuur')->insert([
                    'patientid' => $behandeling['patientid'],
                    'behandelingid' => $behandeling['id'],
                    'nummer' => 'F' . date('Y') . '-' . str_pad($factuurCounter++, 4, '0', STR_PAD_LEFT),
                    'datum' => Carbon::now()->subDays(rand(1, 45))->format('Y-m-d'),
                    'bedrag' => $behandeling['kosten'],
                    'status' => $factuurStatuses[array_rand($factuurStatuses)],
                    'isactief' => 1,
                    'opmerking' => null,
                    'datumaangemaakt' => Carbon::now(),
                    'datumgewijzigd' => Carbon::now(),
                ]);
            }
        }

        $this->command->info('Facturen seeding completed!');
        $this->command->info('Created: ' . count($patients) . ' patients, ' . count($medewerkers) . ' medewerkers, ' . count($behandelingen) . ' behandelingen');
    }
}
