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
            'name'             => $this->type->name,
            'description'      => $this->description,
            'instructors'      => $this->instructors,
            'number_of_members'=> $this->number_of_members,
            'school_id'        => $this->school_id,
            'school'           => $this->whenLoaded('school', function() {
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
