<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'caption',
        'description',
        'path',
        'imageable_id',
        'imageable_type',
    ];
    // img type :
    // App\Models\Facility
    
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
