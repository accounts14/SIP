<?php

namespace App\Models;

use App\Models\Scopes\BrandScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'variant_name',
        'variant_image',
        'product_discount_id',
        'variant_description'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BrandScope);

        $user = Auth::user();
        if ($user) {
            static::creating(function ($column) {
                $column->brand_id = Auth::user()->brand_id;
                $column->created_by = Auth::user()->id;
            });
    
            static::updating(function ($column) {
                $column->modified_by = Auth::user()->id;
            });
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariantSizes()
    {
        return $this->hasMany(ProductVariantSize::class);
    }

    public function material()
    {
        return $this->belongsTo(ProductMaterial::class, 'product_material_id');
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class, 'product_unit_id');
    }

    public function discount()
    {
        return $this->belongsTo(ProductDiscount::class, 'product_discount_id');
    }
}