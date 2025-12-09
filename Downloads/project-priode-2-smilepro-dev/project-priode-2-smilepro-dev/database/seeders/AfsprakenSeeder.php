<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AfsprakenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Voor nu gebruik gewoon numerieke IDs zonder foreign keys
        $afspraken = [
            [
                'patientid' => 1,
                'medewerkerid' => 1,
                'datum' => Carbon::today()->format('Y-m-d'),
                'tijd' => '09:30:00',
                'status' => 'Bevestigd',
                'isactief' => 1,
                'opmerking' => 'Controle afspraak',
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ],
            [
                'patientid' => 2,
                'medewerkerid' => 2,
                'datum' => Carbon::today()->addDay()->format('Y-m-d'),
                'tijd' => '14:00:00',
                'status' => 'Bevestigd',
                'isactief' => 1,
                'opmerking' => null,
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ],
            [
                'patientid' => 3,
                'medewerkerid' => 1,
                'datum' => Carbon::today()->addDays(2)->format('Y-m-d'),
                'tijd' => '10:15:00',
                'status' => 'Geannuleerd',
                'isactief' => 0,
                'opmerking' => 'Patiënt geannuleerd',
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ],
            [
                'patientid' => 4,
                'medewerkerid' => 2,
                'datum' => Carbon::today()->addDays(3)->format('Y-m-d'),
                'tijd' => '11:45:00',
                'status' => 'Bevestigd',
                'isactief' => 1,
                'opmerking' => null,
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ],
            [
                'patientid' => 5,
                'medewerkerid' => 1,
                'datum' => Carbon::today()->addDays(4)->format('Y-m-d'),
                'tijd' => '16:30:00',
                'status' => 'Bevestigd',
                'isactief' => 1,
                'opmerking' => 'Nieuwe patiënt',
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ],
        ];

        DB::table('afspraken')->insert($afspraken);
    }
}
