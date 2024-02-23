<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageEvent;
use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageThreadResource;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $userClass = get_class($user);
        $messages = Message::with('sender')
            ->select(
                DB::raw('MAX(content) as content'),
                DB::raw('MAX(is_read) as is_read'),
                DB::raw('MAX(created_at) as created_at'),
                DB::raw('MAX(thread_id) as thread_id'),
            )
            ->selectRaw("CASE WHEN sender_id = ? AND sender_type = ? THEN recipient_id ELSE sender_id END AS sender_id", [$user->id, $userClass])
            ->selectRaw("CASE WHEN sender_id = ? AND sender_type = ? THEN recipient_type ELSE sender_type END AS sender_type", [$user->id, $userClass])
            ->orderBy('created_at', 'DESC')
            ->groupBy('sender_id', 'recipient_id', 'sender_type', 'recipient_type')
            ->get()
            ->unique("sender_id")
            ->values();

        return MessageResource::collection($messages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $senderUuid = $request->sender_uuid;
        $recipientUuid = $request->recipient_uuid;
        $sender = User::where('uuid', $senderUuid)->first();
        $recipient = User::where('uuid', $recipientUuid)->first();

        if(!$request->thread_uuid) {
            // if this is a new conversation, generate a thread and broadcast it to the opposite user
            $thread = MessageThread::create([
                'uuid'      => Str::uuid(),
                'initiator' => $sender->id,
                'recipient' => $recipient->id,
            ]);
        }
        else {
            $thread = MessageThread::where('uuid', $request->thread_uuid)->first();
        }

        $message = Message::create([
            'thread_id'     => $thread->id,
            'content'       => $request->message,
            'sender_id'     => $sender->id,
            'sender_type'   => get_class($sender),
            'recipient_id'  => $recipient->id,
            'recipient_type' => get_class($recipient),

        ]);

        $thread->load(["latestMessage", "initiatorData", "recipientData"]);
        $broadcastedMessage = new MessageThreadResource($thread);
        $channelName = 'chat-sip-' . $thread->uuid;

        if(!$request->thread_uuid) {
            broadcast(new NewMessageEvent("user.$recipient->uuid", $broadcastedMessage))->toOthers();
        }
        else {
            broadcast(new MessageEvent($channelName, $sender, $recipient, $broadcastedMessage))->toOthers();
        }

        return ['status' => 'Message Sent!', 'message' => $broadcastedMessage];
    }

    /** 
     * Display the specified resource.
     */
    public function show(string $threadUuid)
    {
        $messages = MessageThread::with(["messages.sender", "initiatorData", "recipientData"])
            ->where('uuid', $threadUuid)
            ->first();
        return new MessageThreadResource($messages);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getThreads() {
        $userId = auth()->user()->id;
        $threads = MessageThread::with(['latestMessage'])
            ->where('initiator', $userId)
            ->orWhere('recipient', $userId)
            ->get();
        
        return MessageThreadResource::collection($threads);
    }
}
