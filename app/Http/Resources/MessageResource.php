<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'content'   => $this->content,
            'is_read'   => $this->is_read,
            'type'      => $this->type ?: NULL,
            'sender'  => $this->whenLoaded('sender', function() {
                return [
                    'uuid'  => $this->sender->uuid,
                    'name'  => $this->sender->name,
                ];
            }),
            'created_at'    => $this->created_at,
        ];
    }
}
