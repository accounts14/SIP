<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achievement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'achieved_from',
        'description',
        'achieved_at',
        'achieved_by',
        'level',
        'school_id',
    ];
    
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
