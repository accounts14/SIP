<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolLevel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'prefix'];

    public function schools() :HasMany {
        return $this->HasMany(School::class, 'level', 'id');
    }
}
