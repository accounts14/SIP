<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolLevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'prefix' => $this->prefix,
            'schools' => $this->whenLoaded('schools', function() {
                return SchoolResource::collection(
                    $this->schools()
                        ->where('is_member', '!=', '0')
                        ->orderBy('is_member', 'desc')
                        ->limit(5)
                        ->get()
                );
            }),
        ];
    }
}
