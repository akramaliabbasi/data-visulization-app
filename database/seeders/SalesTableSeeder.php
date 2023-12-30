<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesTableSeeder extends Seeder
{
    public function run()
    {
        // Insert some sample data into the sales table
        for ($i = 1; $i <= 12; $i++) {
            $date = Carbon::create(2024, $i, rand(1, 28)); // Adjust the year as needed

            DB::table('sales')->insert([
                'saleDate' => $date,
                'quantity' => rand(1, 100),
                // Add other columns as needed
            ]);
        }
    }
}
