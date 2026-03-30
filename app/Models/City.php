<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';

    protected $fillable = [
        'name',
        'city_code',
        'province_id',
    ];

    // FIX: Cast province_id ke integer agar konsisten
    protected $casts = [
        'province_id' => 'integer',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'city_id');
    }

    public function schools()
    {
        return $this->hasMany(School::class, 'city_id');
    }
}