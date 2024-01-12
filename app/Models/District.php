<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';
    protected $primaryKey = 'dis_id';
    protected $fillable = ['dis_name', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'dis_id');
    }

    public function subdistricts()
    {
        return $this->hasMany(SubDistrict::class, 'dis_id', 'dis_id');
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }

}
