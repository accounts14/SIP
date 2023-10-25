<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductType extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name'
    ];

    public function product()
    {
        return $this->hasOne(Product::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function productUnits()
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function productMaterials()
    {
        return $this->hasMany(ProductMaterial::class);
    }

    public function productDiscounts()
    {
        return $this->hasMany(ProductDiscount::class);
    }
}
