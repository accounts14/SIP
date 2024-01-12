<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageEvent;
use App\Http\Controllers\Controller;
use App\Models\AnonymousUser;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $senderUuid = $request->sender_uuid;
        $recipientUuid = $request->recipient_uuid;
        // if anonymous user is sending
        if($request->sender_type === 'anonymous_user') {
            // if no records found for this anonymous user, create it first
            if(!$senderUuid) {
                $sender = AnonymousUser::create([
                    'uuid'      =>  Str::uuid(),
                    'username'  =>  'anonymous-user-' . rand(000000, 999999),
                ]);
            }
            else {
                $sender = AnonymousUser::where('uuid', $senderUuid)->first();
            }
            $recipient = User::where('uuid', $recipientUuid)->first();
        }
        // if admin is sending messages
        else {
            $sender = User::where('uuid', $senderUuid)->first();
            $recipient = AnonymousUser::where('uuid', $recipientUuid)->first();
        }

        $message = Message::create([
            'content'       => $request->message,
            'sender_id'     => $sender->id,
            'sender_type'   => get_class($sender),
            'recipient_id'  => $recipient->id,
            'recipient_type' => get_class($recipient),

        ]);

        broadcast(new MessageEvent($sender, $recipient, $message))->toOthers();

        return ['status' => 'Message Sent!', 'message' => $message];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
