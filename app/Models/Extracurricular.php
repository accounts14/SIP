<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Extracurricular extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'instructors',
        'number_of_members',
        'school_id',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
}
