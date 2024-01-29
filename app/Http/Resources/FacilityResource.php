<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'type_id'     => $this->type_id,
            'name'        => $this->type->name,
            'description' => $this->description,
            'condition'   => $this->condition,
            'school_id'   => $this->school_id,
            'school'      => $this->whenLoaded('school', function() {
                return [
                    'name'          => $this->school->name,
                    'type'          => $this->school->type,
                    'accreditation' => $this->school->accreditation,
                    'level'         => $this->school->level,
                    'npsn'          => $this->school->npsn,
                ];
            }),
            'images'      => $this->whenLoaded('images', function() {
                return $this->images;
            }),
        ];
    }
}
