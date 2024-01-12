<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegistrationFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"            => $this->id,
            "school_id"     => $this->school_id,
            "ta"            => $this->ta,
            "title"         => $this->title,
            "description"   => $this->description,
            "logo"          => $this->logo,
            "banner"        => $this->banner,
            "quota"         => $this->quota,
            "registration_fee"   => $this->registration_fee,
            "registration_field" => $this->registration_field,
            "status"        => $this->status,
            "updated_at"    => $this->updated_at,
            "school"        => [
                'name'          => $this->school->name,
                'type'          => $this->school->type,
                'accreditation' => $this->school->accreditation,
                'level'         => $this->school->level,
                'npsn'          => $this->school->npsn,
            ]
        ];
    }
}
