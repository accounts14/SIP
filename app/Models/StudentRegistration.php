<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRegistration extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'school_id',
        'student_id',
        'registration_form_id',
        'registered_by',
        'school_origin',
        'status',
    ];
    
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
    
    public function student()
    {
        return $this->belongsTo(UserCandidate::class, 'student_id');
    }
    
    public function regForm()
    {
        return $this->belongsTo(RegistrationForm::class, 'registration_form_id');
    }
}
