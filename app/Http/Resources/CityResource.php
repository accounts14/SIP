<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            // FIX: Gunakan 'id' dan 'name' agar konsisten dengan frontend
            'id'        => $this->id,
            'name'      => $this->name,
            'city_code' => $this->city_code,

            // Relasi province — hanya muncul jika di-load
            'province'  => $this->whenLoaded('province', function () {
                return new ProvinceResource($this->province);
            }),

            // Relasi districts — hanya muncul jika di-load
            'districts' => $this->whenLoaded('districts', function () {
                return DistrictResource::collection($this->districts);
            }),

            // Relasi schools — hanya muncul jika di-load
            'schools'   => $this->whenLoaded('schools', function () {
                return SchoolResource::collection($this->schools);
            }),
        ];
    }
}