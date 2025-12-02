<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@smilepro.nl',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Create medewerker user
        $medewerker = User::create([
            'name' => 'Medewerker Test',
            'email' => 'medewerker@smilepro.nl',
            'password' => Hash::make('password'),
        ]);
        $medewerker->assignRole('medewerker');

        // Create klant user
        $klant = User::create([
            'name' => 'Klant Test',
            'email' => 'klant@smilepro.nl',
            'password' => Hash::make('password'),
        ]);
        $klant->assignRole('klant');

        echo "Test users created:\n";
        echo "- Admin: admin@smilepro.nl / password\n";
        echo "- Medewerker: medewerker@smilepro.nl / password\n";
        echo "- Klant: klant@smilepro.nl / password\n";
    }
}
