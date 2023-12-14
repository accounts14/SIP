<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'regency_id'];

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function districts()
    {
        return $this->hasMany(District::class);
    }

}
