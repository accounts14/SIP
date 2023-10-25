<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = ['prefix', 'name', 'logo', 'background', 'image', 'title', 'description'];

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
