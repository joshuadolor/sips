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
        \App\Models\Company::factory(5)->create();
        \App\Models\User::factory(8)->create();
        \App\Models\Agent::factory(10)->create();
        \App\Models\Employee::factory(10)->create();
    }
}
