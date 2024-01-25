<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $message = Message::create([
            'content'       => $request->message,
            'sender_id'     => $sender->id,
            'sender_type'   => get_class($sender),
            'recipient_id'  => $recipient->id,
            'recipient_type' => get_class($recipient),

        ]);

        // $message->type = 'received';
        $message->load('sender');
        $broadcastedMessage = new MessageResource($message);

        broadcast(new MessageEvent($sender, $recipient, $broadcastedMessage))->toOthers();

        return ['status' => 'Message Sent!'];
    }

    /** 
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $authenticatedUserId = auth()->user()->id;
        $oppositeUserId = User::where('uuid', $uuid)->first()->id;

        $messages = Message::where('recipient_id', $authenticatedUserId)
            ->where('sender_id', $oppositeUserId)
            ->orWhere(function ($query) use ($authenticatedUserId, $oppositeUserId) {
                $query->where("recipient_id", $oppositeUserId)
                      ->where("sender_id", $authenticatedUserId);
            })
            ->orderBy("created_at", "DESC")
            ->get();

        $messages = $messages->map(function ($message) use ($authenticatedUserId) {
            // Check if the sender_id is the same as $authenticatedUserId
            if ($message->sender_id == $authenticatedUserId) {
                $message->type = 'sent';
            }
            // Check if the recipient_id is the same as $oppositeUserId
            else {
                $message->type = 'received';
            }
        
            return $message;
        });

        return MessageResource::collection($messages);
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
}
