<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'achieved_from' => $this->achieved_from,
            'description'   => $this->description,
            'achieved_at'   => $this->achieved_at,
            'achieved_by'   => $this->achieved_by,
            'level'         => $this->level,
            'school'           => $this->whenLoaded('school', function() {
                return [
                    'name'          => $this->schools->name,
                    'type'          => $this->schools->type,
                    'accreditation' => $this->schools->accreditation,
                    'level'         => $this->schools->level,
                    'npsn'          => $this->schools->npsn,
                ];
            }),
        ];
    }
}
