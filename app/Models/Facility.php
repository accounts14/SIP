<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    
    public function images()
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
}
