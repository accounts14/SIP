<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'district';
    protected $fillable = ['name', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function subdistricts()
    {
        return $this->hasMany(SubDistrict::class, 'district_id', 'id');
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }

}
