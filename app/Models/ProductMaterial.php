<?php

namespace App\Models;

use App\Models\Scopes\BrandScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ProductMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_type_id',
        'name'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function productType()
    {
        return $this->belongsTo(ProductType::class);
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

}
