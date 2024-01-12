<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationForm extends Model
{
    use HasFactory;
    
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
}
