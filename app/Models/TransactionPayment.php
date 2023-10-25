<?php

namespace App\Models;

use App\Models\Scopes\BrandScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class TransactionPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'transaction_id',
        'price',
        'payment_method_id',
        'payment_date',
        'payment_due_date',
        'payment_status',
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

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transactionPaymentMethod()
    {
        return $this->belongsTo(TransactionPaymentMethod::class);
    }

}
