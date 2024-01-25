<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExtracurricularResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'description'      => $this->description,
            'instructors'      => $this->instructors,
            'number_of_members'=> $this->number_of_members,
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
