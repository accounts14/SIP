<?php

namespace Database\Seeders;

use App\Models\School;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        School::firstOrCreate([
            'name' => 'Secrash School',
            'type' => 'Swasta',
            'accreditation' => 'A',
            'level' => 7,
            'established' => '',
            'npsn' => '1333333333333337',
            'headmaster' => 'Acil.Dev', 
            'class' => '18',
            'curriculum' => 'K13',
            'student' => '760',
            'location' => 'Jln. Banten Kp.40',
            'longitude' => '105.246391',
            'latitude' => '-5.433050',
            'link_location' => '',
            'telephone' => '085161561337',
            'web' => 'https://www.secrash.com',
            'motto' => '',
            // 'content_array' => '',
            'school_status' => '1',
            'avatar' => '',
            'banner' => '',
            'slug' => 'secrash-school'
        ]);
    }
}

