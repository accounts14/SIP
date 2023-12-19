<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function schoolLevels() :BelongsTo {
        return $this->belongsTo(SchoolLevel::class, 'level');
    }

    public function province() :BelongsTo {
        return $this->belongsTo(Province::class, 'province_id', 'prov_id');
    }
}
