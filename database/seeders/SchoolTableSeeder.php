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
        School::create([
            'name' => 'Secrash School',
            'type' => 'Swasta',
            'accreditation' => 'A',
            'level' => 'SMK',
            'established' => '',
            'npsn' => '1333333333333337',
            'headmaster' => 'Acil.Dev', 
            'class' => '1337',
            'curriculum' => '2021',
            'student' => '10000',
            'location' => 'Jln. Banten Kp.40',
            'longitude' => '',
            'latitude' => '',
            'link_location' => '',
            'telephone' => '085161561337',
            'web' => 'https://www.secrash.com',
            'motto' => '',
            'content_array' => '',
            'school_status' => 'Active',
            'avatar' => '',
            'banner' => '',
            'slug' => 'secrash-school'
        ]);
    }
}

