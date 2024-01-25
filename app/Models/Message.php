<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'sender_id', 'sender_type', 'recipient_id', 'recipient_type'
    ];

    public function sender() {
        return $this->morphTo();
    }

    public function recipient() {
        return $this->morphTo();
    }
}
