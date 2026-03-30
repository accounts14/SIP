<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    // FIX: Tambahkan baris ini
    protected $table = 'district';

    protected $fillable = [
        'name',
        'district_code',
        'city_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}