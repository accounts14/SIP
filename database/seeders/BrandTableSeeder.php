<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::truncate();

        Brand::firstOrCreate([
            'id' => 1,
            'prefix' => 'LWS',
            'name' => 'Lexy Wears',
        ]);

        Brand::firstOrCreate([
            'id' => 2,
            'prefix' => 'LAS',
            'name' => 'Lexy Artisan',
        ]);
    }
}
