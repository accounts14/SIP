<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtracurricularType extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable =['name', 'description', 'value', 'icon'];

    protected $hidden = [
        'deleted_at',
    ];

    public function facilities() {
        return $this->hasMany(Extracurricular::class, 'type_id');
    }
}
