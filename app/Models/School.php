<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'accreditation',
        'level',
        'established',
        'npsn',
        'headmaster', 
        'class',
        'curriculum',
        'student',
        'location',
        'longitude',
        'latitude',
        'link_location',
        'telephone',
        'web',
        'motto',
        'content_array',
        'school_status',
        'avatar',
        'banner',
        'slug',
    ];
}
