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

    public function opposite() {
        return $this->morphTo(__FUNCTION__, 'sender_type', 'sender_id');
    }

    public function getOppositesTypeAttribute() {
        $user = auth()->user();
        return ($this->sender_id == $user->id) ? $this->recipient_type : $this->sender_type;
    }
}
