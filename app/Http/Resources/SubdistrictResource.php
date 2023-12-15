<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubdistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subdis_id'     => $this->subdis_id,
            'subdis_name'   => $this->subdis_name,
            'district'  => $this->whenLoaded('district', function() {
                return new DistrictResource($this->district);
            }),
            'schools'   => $this->whenLoaded('schools', function() {
                return SchoolResource::collection($this->schools);
            }),
        ];
    }
}
