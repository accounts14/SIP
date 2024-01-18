<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(School::class, 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(UserCandidate::class, 'student_id');
    }
    
    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (auth()->user()->role == 'member') {
                $builder->where('user_id', auth()->id());
            }
        });
    }
}
