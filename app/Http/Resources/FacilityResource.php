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
            'name'        => $this->type->name,
            'description' => $this->description,
            'condition'   => $this->condition,
            'school'      => $this->whenLoaded('school', function() {
                return [
                    'name'          => $this->schools->name,
                    'type'          => $this->schools->type,
                    'accreditation' => $this->schools->accreditation,
                    'level'         => $this->schools->level,
                    'npsn'          => $this->schools->npsn,
                ];
            }),
            'images'      => $this->whenLoaded('images', function() {
                return [
                    'caption'        => $this->images->caption,
                    'description'    => $this->images->description,
                    'path'           => $this->images->path,
                ];
            }),
        ];
    }
}
