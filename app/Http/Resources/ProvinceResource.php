<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProvinceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'prov_id'   => $this->prov_id,
            'prov_name' => $this->prov_name,
            'schools'   => $this->whenLoaded('schools', function() {
                return SchoolResource::collection($this->schools);
            }),
            'cities'    => $this->whenLoaded('cities', function() {
                return CityResource::collection($this->cities);
            }),
        ];
    }
}
