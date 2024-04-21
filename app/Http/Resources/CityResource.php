<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'city_id'   => $this->id,
            'city_name' => $this->name,
            'province'  => $this->whenLoaded('province', function() {
                return new ProvinceResource($this->province);
            }),
            'schools'   => $this->whenLoaded('schools', function() {
                return SchoolResource::collection($this->schools);
            }),
            'districts' => $this->whenLoaded('districts', function() {
                return DistrictResource::collection($this->districts);
            }),
        ];
    }
}
