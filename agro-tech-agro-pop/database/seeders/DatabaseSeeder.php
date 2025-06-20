<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\User;
use Database\Factories\MachinesFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
           MachinesSeeder::class
        ]);

        Machine::factory(count: 100)->create();
    }
}
