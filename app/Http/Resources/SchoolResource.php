<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
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
            'type'          => $this->type,
            'accreditation' => $this->accreditation,
            'level'         => $this->level,
            'established'   => $this->established,
            'npsn'          => $this->npsn,
            'headmaster'    => $this->headmaster,
            'class'         => $this->class,
            'curriculum'    => $this->curriculum,
            'student'       => $this->student,
            'location'      => $this->location,
            'longitude'     => $this->longitude,
            'latitude'      => $this->latitude,
            'link_location' => $this->link_location,
            'telephone'     => $this->telephone,
            'web'           => $this->web,
            'motto'         => $this->motto,
            'content_array' => $this->content_array,
            'school_status' => $this->school_status,
            'avatar'        => $this->avatar,
            'banner'        => $this->banner,
            'slug'          => $this->slug,
            'testimonies'   => $this->whenLoaded('testimonies', function() {
                return TestimonyResource::collection($this->testimonies);
            }),
        ];
    }
}
