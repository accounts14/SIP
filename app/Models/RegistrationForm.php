<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistrationForm extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'school_id',
        'ta',
        'title',
        'description',
        'logo',
        'banner',
        'quota',
        'registration_fee',
        'registration_field',
        'status',
    ];
    
    public function registration()
    {
        return $this->hasMany(StudentRegistration::class, 'registration_form_id');
    }

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
