<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

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
        'province_id',
        'city_id',
        'district_id',
        'subdistrict_id',
        'longitude',
        'latitude',
        'link_location',
        'telephone',
        'web',
        'motto',
        'content_array',
        'school_status',
        'is_member',
        'avatar',
        'banner',
        'slug',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function scopeFindByIdOrSlug($query, $param)
    {
        if (is_numeric($param)) {
            return $query->where('id', $param)->first();
        } else {
            return $query->where('slug', $param)->first();
        }
    }

    public function testimonies() {
        return $this->hasMany(Testimony::class);
    }

    public function registration() {
        return $this->hasMany(StudentRegistration::class, 'school_id');
    }

    public function facilities() {
        return $this->hasMany(Facility::class, 'school_id');
    }

    public function extracurriculars() {
        return $this->hasMany(Extracurricular::class, 'school_id');
    }

    public function achievements() {
        return $this->hasMany(Achievement::class, 'school_id');
    }

    public function regForm() {
        return $this->hasMany(RegistrationForm::class, 'school_id');
    }

    public function teachers() {
        return $this->hasMany(Teacher::class, 'school_id');
    }
    
    public function schoolLevels() :BelongsTo {
        return $this->belongsTo(SchoolLevel::class, 'level');
    }

    public function province() :BelongsTo {
        return $this->belongsTo(Province::class, 'province_id', 'prov_id');
    }

    public function city() :BelongsTo {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }
}
