<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    
    // protected $primaryKey = 'city_id';
    protected $table = 'city';
    protected $fillable = ['name', 'province_id'];

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function districts()
    {
        return $this->hasMany(District::class, 'city_id', 'id');
    }

}
