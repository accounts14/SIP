<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'caption',
        'description',
        'path',
        'imageable_id',
        'imageable_type',
        'school_id',
    ];
    // img type :
    // App\Models\Facility
    
    public function imageable()
    {
        return $this->morphTo();
    }
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
}
