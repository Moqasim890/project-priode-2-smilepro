<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AfsprakenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patientIds = DB::table('patient')->pluck('id')->toArray();
        $medewerkerIds = DB::table('medewerker')->pluck('id')->toArray();

        if (empty($patientIds)) {
            $this->command->warn('Geen patiënten gevonden.');
            return;
        }

        if (empty($medewerkerIds)) {
            $this->command->warn('Geen medewerkers gevonden.');
            return;
        }

        // Slechts 6 afspraken
        $afspraken = [
            [
                'patientid' => $patientIds[0] ?? $patientIds[0],
                'medewerkerid' => $medewerkerIds[0],
                'datum' => now()->addDays(1)->toDateString(),
                'tijd' => '09:00:00',
                'status' => 'Bevestigd',
                'isactief' => 1,
                'opmerking' => 'Controle afspraak',
            ],
            [
                'patientid' => $patientIds[1] ?? $patientIds[0],
                'medewerkerid' => $medewerkerIds[1] ?? $medewerkerIds[0],
                'datum' => now()->addDays(2)->toDateString(),
                'tijd' => '10:30:00',
                'status' => 'Bevestigd',
                'isactief' => 1,
                'opmerking' => 'Gebitsreiniging',
            ],
            [
                'patientid' => $patientIds[2] ?? $patientIds[0],
                'medewerkerid' => $medewerkerIds[0],
                'datum' => now()->addDays(3)->toDateString(),
                'tijd' => '14:00:00',
                'status' => 'Bevestigd',
                'isactief' => 1,
                'opmerking' => 'Vulling',
            ],
            [
                'patientid' => $patientIds[3] ?? $patientIds[0],
                'medewerkerid' => $medewerkerIds[1] ?? $medewerkerIds[0],
                'datum' => now()->addDays(5)->toDateString(),
                'tijd' => '11:00:00',
                'status' => 'Geannuleerd',
                'isactief' => 0,
                'opmerking' => 'Patiënt afgemeld',
            ],
            [
                'patientid' => $patientIds[4] ?? $patientIds[0],
                'medewerkerid' => $medewerkerIds[0],
                'datum' => now()->addDays(7)->toDateString(),
                'tijd' => '15:30:00',
                'status' => 'Bevestigd',
                'isactief' => 1,
                'opmerking' => 'Nieuwe patiënt intake',
            ],
            [
                'patientid' => $patientIds[5] ?? $patientIds[0],
                'medewerkerid' => $medewerkerIds[1] ?? $medewerkerIds[0],
                'datum' => now()->addDays(10)->toDateString(),
                'tijd' => '09:30:00',
                'status' => 'Bevestigd',
                'isactief' => 1,
                'opmerking' => 'Controle na behandeling',
            ],
        ];

        foreach ($afspraken as $afspraak) {
            $afspraak['datumaangemaakt'] = now();
            $afspraak['datumgewijzigd'] = now();
            DB::table('afspraken')->insert($afspraak);
        }

        $this->command->info('6 afspraken aangemaakt!');
    }
}
