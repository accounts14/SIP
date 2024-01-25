<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['school_id', 'type_id', 'description', 'condition'];

    public function type()
    {
        return $this->belongsTo(FacilityType::class, 'type_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    
    public function images(): MorphMany
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }
}
