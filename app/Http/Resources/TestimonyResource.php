<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonyResource extends JsonResource
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
            'user_id'   => $this->user_id,
            'user_name' => $this->user_name,
            'school_id' => $this->school_id,
            'content'   => $this->content,
            'user'      => $this->whenLoaded('user', function() {
                return new UserResource($this->user);
            }),
        ];
    }
}
