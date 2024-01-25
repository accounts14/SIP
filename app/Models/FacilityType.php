<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable =['name', 'description', 'value'];

    public function facilities() {
        return $this->hasMany(Facility::class, 'type_id');
    }
}
