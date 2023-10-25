<?php

namespace App\Models;

use App\Models\Scopes\BrandScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'transaction_code',
        'transaction_date',
        'customer_id',
        'created_customer_id',
        'address',
        'grand_total_price',
        'grand_total_cost',
        'actual_price',
        'transaction_status_id',
        'transaction_description',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdCustomer()
    {
        return $this->belongsTo(CreatedCustomer::class);
    }
    
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function transactionCosts()
    {
        return $this->hasMany(TransactionCost::class);
    }

    public function transactionPayments()
    {
        return $this->hasMany(Payment::class);
    }

    public function transactionStatus()
    {
        return $this->belongsTo(TransactionStatus::class);
    }
}