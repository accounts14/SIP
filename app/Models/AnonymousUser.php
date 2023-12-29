<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnonymousUser extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'username'];

    public function sentMessages() {
        return $this->morphMany(Message::class, 'sender');
    }

    public function receivedMessages() {
        return $this->morphMany(Message::class, 'recipient');
    }
}
