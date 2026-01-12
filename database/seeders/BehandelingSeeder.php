<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BehandelingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Voegt test behandelingen toe voor alle patiënten
     */
    public function run(): void
    {
        // Haal alle patiënten op (behalve test medewerkers)
        $patienten = DB::table('patient as pt')
            ->join('persoon as ps', 'pt.persoonid', '=', 'ps.id')
            ->whereNotIn('ps.voornaam', ['Praktijkmanager', 'Dr. Tandarts', 'Mondhygiënist', 'Assistent', 'Patiënt'])
            ->select('pt.id', 'ps.voornaam')
            ->get();

        $behandelingen = [];

        foreach ($patienten as $patient) {
            // Controle behandelingen (behandeld)
            $behandelingen[] = [
                'medewerkerid' => null,
                'patientid' => $patient->id,
                'datum' => now()->subDays($patient->id * 7)->format('Y-m-d'),
                'tijd' => sprintf('%02d:00:00', 8 + ($patient->id % 9)),
                'behandelingtype' => 'Controles',
                'omschrijving' => "Controle voor {$patient->voornaam}",
                'kosten' => 75.00,
                'status' => 'Behandeld',
                'isactief' => 1,
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ];

            // Gebitsreiniging of Vullingen (behandeld)
            $behandelingen[] = [
                'medewerkerid' => null,
                'patientid' => $patient->id,
                'datum' => now()->subDays($patient->id * 5)->format('Y-m-d'),
                'tijd' => sprintf('%02d:30:00', 9 + ($patient->id % 8)),
                'behandelingtype' => $patient->id % 2 == 0 ? 'Gebitsreiniging' : 'Vullingen',
                'omschrijving' => $patient->id % 2 == 0 ? 'Tandsteen verwijderen' : 'Vulling plaatsen',
                'kosten' => $patient->id % 2 == 0 ? 85.00 : 150.00,
                'status' => 'Behandeld',
                'isactief' => 1,
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ];

            // Toekomstige gebitsreiniging (onbehandeld)
            $behandelingen[] = [
                'medewerkerid' => null,
                'patientid' => $patient->id,
                'datum' => now()->addDays($patient->id * 3)->format('Y-m-d'),
                'tijd' => sprintf('%02d:00:00', 10 + ($patient->id % 7)),
                'behandelingtype' => 'Gebitsreiniging',
                'omschrijving' => 'Geplande reiniging',
                'kosten' => 85.00,
                'status' => 'Onbehandeld',
                'isactief' => 1,
                'datumaangemaakt' => now(),
                'datumgewijzigd' => now(),
            ];

            // Wortelkanaalbehandelingen (elke 3e patiënt)
            if ($patient->id % 3 == 0) {
                $kies = ['16', '26', '36', '46'][$patient->id % 4];
                $behandelingen[] = [
                    'medewerkerid' => null,
                    'patientid' => $patient->id,
                    'datum' => now()->subDays($patient->id * 10)->format('Y-m-d'),
                    'tijd' => '14:00:00',
                    'behandelingtype' => 'Wortelkanaalbehandelingen',
                    'omschrijving' => "Wortelkanaalbehandeling kies {$kies}",
                    'kosten' => 450.00,
                    'status' => 'Behandeld',
                    'isactief' => 1,
                    'datumaangemaakt' => now(),
                    'datumgewijzigd' => now(),
                ];
            }

            // Orthodontie behandelingen (elke 4e patiënt)
            if ($patient->id % 4 == 0) {
                $behandelingen[] = [
                    'medewerkerid' => null,
                    'patientid' => $patient->id,
                    'datum' => now()->subDays($patient->id * 20)->format('Y-m-d'),
                    'tijd' => '10:30:00',
                    'behandelingtype' => 'Orthodontie',
                    'omschrijving' => "Beugel controle - {$patient->voornaam}",
                    'kosten' => 125.00,
                    'status' => 'Behandeld',
                    'isactief' => 1,
                    'datumaangemaakt' => now(),
                    'datumgewijzigd' => now(),
                ];

                // Toekomstige orthodontie (elke 7e)
                if ($patient->id % 7 == 0) {
                    $behandelingen[] = [
                        'medewerkerid' => null,
                        'patientid' => $patient->id,
                        'datum' => now()->addDays(14)->format('Y-m-d'),
                        'tijd' => '11:00:00',
                        'behandelingtype' => 'Orthodontie',
                        'omschrijving' => 'Nieuwe beugel plaatsing',
                        'kosten' => 850.00,
                        'status' => 'Onbehandeld',
                        'isactief' => 1,
                        'datumaangemaakt' => now(),
                        'datumgewijzigd' => now(),
                    ];
                }
            }

            // Extra vullingen (elke 5e patiënt - onbehandeld)
            if ($patient->id % 5 == 0) {
                $behandelingen[] = [
                    'medewerkerid' => null,
                    'patientid' => $patient->id,
                    'datum' => now()->addDays($patient->id * 5)->format('Y-m-d'),
                    'tijd' => '15:00:00',
                    'behandelingtype' => 'Vullingen',
                    'omschrijving' => 'Meerdere vullingen nodig - intake',
                    'kosten' => 175.00,
                    'status' => 'Onbehandeld',
                    'isactief' => 1,
                    'datumaangemaakt' => now(),
                    'datumgewijzigd' => now(),
                ];
            }

            // Uitgestelde behandelingen (elke 6e patiënt)
            if ($patient->id % 6 == 0) {
                $behandelingen[] = [
                    'medewerkerid' => null,
                    'patientid' => $patient->id,
                    'datum' => now()->addDays(30)->format('Y-m-d'),
                    'tijd' => '09:00:00',
                    'behandelingtype' => $patient->id % 2 == 0 ? 'Gebitsreiniging' : 'Controles',
                    'omschrijving' => 'Uitgesteld op verzoek patiënt',
                    'kosten' => $patient->id % 2 == 0 ? 85.00 : 75.00,
                    'status' => 'Uitgesteld',
                    'isactief' => 1,
                    'datumaangemaakt' => now(),
                    'datumgewijzigd' => now(),
                ];
            }
        }

        // Insert alle behandelingen
        DB::table('behandeling')->insert($behandelingen);

        $this->command->info('✓ ' . count($behandelingen) . ' behandelingen succesvol toegevoegd!');
    }
}
