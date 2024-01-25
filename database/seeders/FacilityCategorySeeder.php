<?php

namespace Database\Seeders;

use App\Models\FacilityType;
use Illuminate\Database\Seeder;

class FacilityCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FacilityType::insert([
            ['name' => 'Gedung', 'description' => 'Status kepemilikan, lokasi, luas, dll.'],
            ['name' => 'Ruang Kelas', 'description' => 'Jumlah, kebersihan, dll.'],
            ['name' => 'Peralatan Belajar', 'description' => 'Meja, kursi, papan tulis, dll.'],
            ['name' => 'Perpustakaan', 'description' => 'Jumlah, sistem administrasi, dll.'],
            ['name' => 'UKS', 'description' => 'Ketersediaan perlengkapaan P3K.'],
            ['name' => 'Olahraga', 'description' => 'lapangan/gor olahraga, atau fasilitas lainnya.'],
            ['name' => 'Laboratorium', 'description' => 'Lab. Komputer/IPA atau lainnya.'],
            ['name' => 'Tempat Ibadah', 'description' => 'Ketersediaan tempat ibadah.'],
            ['name' => 'Toilet & Kamar mandi', 'description' => 'Jumlah, kebersihan, dll.'],
            ['name' => 'Kantin', 'description' => 'Jumlah, kelengkapan, kebersihan, dll.'],
            ['name' => 'Lahan Parkir', 'description' => 'Ketersediaan, luas, dll.'],
        ]);
    }
}
