<?php

namespace Database\Seeders;

<<<<<<< HEAD
use Illuminate\Database\Seeder;
=======
use App\Models\Role;
use App\Models\User;
use App\Models\Person;
use App\Models\Medewerker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD
        $this->call([
            PersoonSeeder::class,
            PatientSeeder::class,
            MedewerkerSeeder::class,
            AfsprakenSeeder::class,
        ]);
    }
}
=======
        // 1. Zorg dat alle rollen bestaan
        $roles = [
            'Patiënt'            => 'Standaard patiënt in de praktijk',
            'Praktijkmanagement' => 'Beheerder van de praktijk',
            'Tandarts'           => 'Tandarts',
            'Mondhygiënist'      => 'Mondhygiënist',
            'Assistent'          => 'Assistent in de praktijk',
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate(
                ['name' => $name],
                ['description' => $description]
            );
        }

        // 2. Praktijkmanager gebruiker aanmaken (voor demo / testen)
        $admin = User::firstOrCreate(
            ['email' => 'praktijkmanager@smilepro.test'],
            [
                'name'     => 'Praktijk Manager',
                'password' => Hash::make('password'), // wachtwoord: "password"
            ]
        );

        // 3. Praktijkmanagement rol koppelen aan deze gebruiker
        $praktijkRole = Role::where('name', 'Praktijkmanagement')->first();

        if ($praktijkRole && ! $admin->roles()->where('roles.id', $praktijkRole->id)->exists()) {
            $admin->roles()->attach($praktijkRole);
        }

        // 4. Alleen proberen persoon / medewerker te maken als de tabellen bestaan
        if (Schema::hasTable('persoon')) {
            $person = Person::firstOrCreate(
                ['gebruikerid' => $admin->id],
                [
                    'voornaam'      => 'Praktijk',
                    'tussenvoegsel' => null,
                    'achternaam'    => 'Manager',
                    'geboortedatum' => '1990-01-01',
                    'isactief'      => 1,
                    'opmerking'     => 'Standaard praktijkmanager gebruiker voor test/doeleinden.',
                ]
            );
        } else {
            $person = null;
        }

        if ($person && Schema::hasTable('medewerker')) {
            Medewerker::firstOrCreate(
                ['nummer' => 'M-0001'],
                [
                    'persoonid'       => $person->id,
                    'medewerkertype'  => 'Praktijkmanagement',
                    'specialisatie'   => 'Praktijkmanager',
                    'beschikbaarheid' => 'Maandag t/m vrijdag 09:00 - 17:00',
                    'isactief'        => 1,
                    'opmerking'       => 'Demo medewerker voor overzicht medewerkers.',
                ]
            );
        }

        // 5. Extra demo-medewerkers (alleen op basis van users + rollen)
        $extraStaff = [
            ['name' => 'Jan de Vries',        'email' => 'jan@smilepro.test',      'role' => 'Tandarts'],
            ['name' => 'Sophie Vermeer',      'email' => 'sophie@smilepro.test',   'role' => 'Mondhygiënist'],
            ['name' => 'Mark Visser',         'email' => 'mark@smilepro.test',     'role' => 'Assistent'],
            ['name' => 'Lisa van Dongen',     'email' => 'lisa@smilepro.test',     'role' => 'Assistent'],
            ['name' => 'Tom van der Meer',    'email' => 'tom@smilepro.test',      'role' => 'Tandarts'],
            ['name' => 'Eva Jansen',          'email' => 'eva@smilepro.test',      'role' => 'Mondhygiënist'],
        ];

        foreach ($extraStaff as $staff) {
            $user = User::firstOrCreate(
                ['email' => $staff['email']],
                [
                    'name'     => $staff['name'],
                    'password' => Hash::make('password'), // allemaal zelfde test-wachtwoord
                ]
            );

            $role = Role::where('name', $staff['role'])->first();

            if ($role && ! $user->roles()->where('roles.id', $role->id)->exists()) {
                $user->roles()->attach($role);
            }
        }
    }
}
>>>>>>> e32ff75 (Add Overzicht Medewerkers feature: views, controller, seeder, happy/unhappy flow)
