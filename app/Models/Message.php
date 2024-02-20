<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'sender_id', 'sender_type', 'recipient_id', 'recipient_type', 'thread_id'
    ];

    public function sender() {
        return $this->morphTo();
    }

    public function recipient() {
        return $this->morphTo();
    }

    public function thread() :BelongsTo {
        return $this->belongsTo(MessageThread::class);
    }
}
