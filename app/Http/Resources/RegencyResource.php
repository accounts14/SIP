<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegencyResource extends JsonResource
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
            'province'   => $this->whenLoaded('province', function() {
                return new ProvinceResource($this->province);
            }),
            'schools'   => $this->whenLoaded('schools', function() {
                return SchoolResource::collection($this->schools);
            }),
            'cities' => $this->whenLoaded('cities', function() {
                return CityResource::collection($this->cities);
            }),
        ];
    }
}
