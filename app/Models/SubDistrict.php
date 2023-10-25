<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    use HasFactory;

    protected $primaryKey = 'subdis_id';

    public function districts()
    {
        return $this->belongsTo(District::class, 'subdis_id');
    }
}
