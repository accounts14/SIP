<?php

namespace Database\Seeders;

use App\Models\TransactionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TransactionStatus::firstOrCreate([
            'brand_id' => 1,
            'name' => 'Pending',
        ]);
        TransactionStatus::firstOrCreate([
            'brand_id' => 1,
            'name' => 'On Progress',
        ]);
        TransactionStatus::firstOrCreate([
            'brand_id' => 1,
            'name' => 'Success',
        ]);
        TransactionStatus::firstOrCreate([
            'brand_id' => 1,
            'name' => 'Canceled',
        ]);
    }
}
