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
            'school_status' => $this->school_status,
            'avatar'        => $this->avatar ? url($this->avatar) : null,
            'banner'        => $this->banner ? url($this->banner) : null,
            'slug'          => $this->slug,
            'province_id'   => $this->province_id,
            'province_name' => $this->province->prov_name ?? null,
            'city_id'       => $this->city_id,
            'city_name'     => $this->city->city_name ?? null,
            'district_id'   => $this->district_id,
            'district_name' => $this->district->dis_name ?? null,
            'subdistrict_id'=> $this->subdistrict_id,
            'subdistrict_name' => $this->subdistrict->subdis_name ?? null,
            'teachers_count'=> $this->whenCounted('teachers'),
            'city'          => $this->city,
            'superadmin'    => $this->whenLoaded('superadmin', function() {
                return new UserResource($this->superadmin);
            }),
            'testimonies'   => $this->whenLoaded('testimonies', function() {
                return TestimonyResource::collection($this->testimonies);
            }),
            'school_levels' => $this->whenLoaded('schoolLevels', function() {
                return $this->schoolLevels;
            }),
            'facilities'    => $this->whenLoaded('facilities', function() {
                return $this->facilities;
            }),
            'extracurriculars'=> $this->whenLoaded('extracurriculars', function() {
                return $this->extracurriculars;
            }),
            'achievements'  => $this->whenLoaded('achievements', function() {
                return $this->achievements;
            }),
            'is_member' => $this->is_member ? true : false,
        ];
    }
}
