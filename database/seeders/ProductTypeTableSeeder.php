<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductType::firstOrCreate([
            'id' => 1,
            'name' => 'Product',
        ]);
        ProductType::firstOrCreate([
            'id' => 2,
            'name' => 'Property Rentals',
        ]);
        ProductType::firstOrCreate([
            'id' => 3,
            'name' => 'Course',
        ]);
        ProductType::firstOrCreate([
            'id' => 4,
            'name' => 'Subscribe',
        ]);
        ProductType::firstOrCreate([
            'id' => 5,
            'name' => 'Food',
        ]);
    }
}
