<?php

namespace App\Models;

use App\Models\Scopes\SchoolScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nik',
        'nama',
        'nuptk',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'jk',
        'agama',
        'alamat',
        'no_hp',
        'email',
        'kewarganegaraan',
        'jurusan',
        'keahlian',
        'school_id',
    ];
    
    public function school() :BelongsTo {
        return $this->belongsTo(School::class, 'school_id');
    }
    
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new SchoolScope);
    }
}
