<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentRegistration extends Model
{
    use HasFactory, SoftDeletes;
    
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
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
}
