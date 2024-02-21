<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MessageThread extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'initiator', 'recipient'];

    public function messages() :HasMany {
        return $this->hasMany(Message::class, 'thread_id', 'id');
    }

    public function latestMessage() :HasOne {
        return $this->hasOne(Message::class, 'thread_id', 'id')->latestOfMany();
    }

    public function initiatorData() :BelongsTo {
        return $this->belongsTo(User::class, 'initiator', 'id');
    }

    public function recipientData() :BelongsTo {
        return $this->belongsTo(User::class, 'recipient', 'id');
    }
}
