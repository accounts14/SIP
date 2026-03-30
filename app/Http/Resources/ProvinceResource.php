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
        'prov_id'   => $this->id,
        'prov_name' => $this->name,
        'prov_code' => $this->prov_code,  // ← tambah ini
        'schools'   => $this->whenLoaded('schools', fn() => SchoolResource::collection($this->schools)),
        'cities'    => $this->whenLoaded('cities',  fn() => CityResource::collection($this->cities)),
    ];
}
}
