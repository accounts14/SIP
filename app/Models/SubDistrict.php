<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    use HasFactory;

    protected $fillable = ['subdis_name', 'dis_id'];

    protected $table = 'subdistricts';

    public function district(){
        return $this->belongsTo(District::class, 'dis_id', 'dis_id');
    }

    public function schools()
    {
        return $this->hasMany(School::class, 'subdistrict_id');
    }
}
