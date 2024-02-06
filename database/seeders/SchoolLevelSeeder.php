<?php

namespace Database\Seeders;

use App\Models\SchoolLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('school_levels')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        SchoolLevel::insert([
            ['name' => 'Pendidikan Anak Usia Dini', 'prefix' => 'PAUD', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Taman Kanak-kanak', 'prefix' => 'TK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sekolah Dasar', 'prefix' => 'SD', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Madrasah Ibtidaiyah', 'prefix' => 'MI', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sekolah Menengah Pertama', 'prefix' => 'SMP', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Madrasah Tsanawiyah', 'prefix' => 'MTs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sekolah Menengah Atas', 'prefix' => 'SMA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Madrasah Aliyah', 'prefix' => 'MA', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sekolah Menengah Kejuruan', 'prefix' => 'SMK', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Madrasah Aliyah Kejuruan', 'prefix' => 'MAK', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
