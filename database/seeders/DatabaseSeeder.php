<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SalesTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Other seeders...
            SalesTableSeeder::class,
        ]);
    }
}
