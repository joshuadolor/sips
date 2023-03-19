<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $freshInstall = env('CLEAN_DATA', false);

        if ($freshInstall) {
            \App\Models\User::create([
                'first_name' => "Sips",
                'last_name' => "Admin",
                'middle_name' => "Super",
                'email' => env('ADMIN_EMAIL', "admin@easybizit.net"),
                'email_verified_at' => now(),
                'is_active' => 1,
                'role' => 2,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            ]);
            return;
        }

        \App\Models\Company::factory(15)->create();
        \App\Models\User::create([
            'first_name' => "Sips",
            'last_name' => "Admin",
            'middle_name' => "Super",
            'email' => "superAdminUser@sips.com",
            'email_verified_at' => now(),
            'is_active' => 1,
            'role' => 2,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $companyIdTest = 2;
        \App\Models\User::create([
            'first_name' => "Sips",
            'last_name' => "Admin",
            'middle_name' => "Super",
            'email' => "adminUser@sips.com",
            'email_verified_at' => now(),
            'is_active' => 1,
            'role' => 1,
            'company_id' => $companyIdTest,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        \App\Models\User::create([
            'first_name' => "Sips",
            'last_name' => "Admin",
            'middle_name' => "Super",
            'email' => "normalUser@sips.com",
            'email_verified_at' => now(),
            'is_active' => 1,
            'role' => 0,
            'company_id' => $companyIdTest,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        \App\Models\User::factory(20)->create();
        \App\Models\Agent::factory(20)->create();
        \App\Models\Employee::factory(20)->create();
        \App\Models\Product::factory(17)->create();
    }
}
