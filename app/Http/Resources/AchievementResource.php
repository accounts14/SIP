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
            'school_id'     => $this->school_id,
            'school'        => $this->whenLoaded('school', function() {
                return [
                    'name'          => $this->school->name,
                    'type'          => $this->school->type,
                    'accreditation' => $this->school->accreditation,
                    'level'         => $this->school->level,
                    'npsn'          => $this->school->npsn,
                ];
            }),
        ];
    }
}
