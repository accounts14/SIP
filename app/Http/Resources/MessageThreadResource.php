<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageThreadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'uuid'      => $this->uuid,
            'initiatorData' => new UserResource($this->initiatorData),
            'recipientData' => new UserResource($this->recipientData),
            'messages'  => $this->whenLoaded('messages', function() {
                return MessageResource::collection($this->messages);
            }),
            'latestMessage'  => $this->whenLoaded('latestMessage', function() {
                return $this->latestMessage;
            }),
        ];
    }
}
