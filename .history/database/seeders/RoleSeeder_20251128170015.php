<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'description' => 'Administrator met volledige toegang tot het systeem',
            ],
            [
                'name' => 'medewerker',
                'description' => 'Medewerker met toegang tot medewerker functies',
            ],
            [
                'name' => 'klant',
                'description' => 'Klant met standaard toegang',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
