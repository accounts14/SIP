<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'city'   => $this->whenLoaded('city', function() {
                return new CityResource($this->city);
            }),
            'schools'   => $this->whenLoaded('schools', function() {
                return SchoolResource::collection($this->schools);
            }),
            'subdistricts' => $this->whenLoaded('subdistricts', function() {
                return SubdistrictResource::collection($this->subdistricts);
            }),
        ];
    }
}
