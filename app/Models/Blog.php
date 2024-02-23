<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'blog';
    protected $fillable = [
        'title',
        'slug',
        'content',
        'for_member',
        'category',
        'tags',
        'school_id',
        'publisher',
        'published_at',
    ];
    
    protected $hidden = [
        'deleted_at',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function publishUser()
    {
        return $this->belongsTo(User::class, 'publisher');
    }
    
    public function images(): MorphMany
    {
        return $this->morphMany(Gallery::class, 'imageable');
    }
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
}
