<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $cities = City::with('province', 'districts');

        if ($request->q) {
            $cities->where('name', 'like', "%{$request->q}%");
        }

        // FIX: Filter by province_id, cast ke integer agar query benar
        if ($request->prov || $request->province_id) {
            $cities->where('province_id', (int) ($request->prov ?? $request->province_id));
        }

        if ($request->limit) {
            return CityResource::collection($cities->paginate((int) $request->limit));
        }

        return CityResource::collection($cities->get());
    }

    public function store(CityRequest $request)
    {
        $validated = $request->validated();

        // FIX: Pastikan province_id tersimpan sebagai integer
        $validated['province_id'] = (int) $validated['province_id'];

        $city = City::create($validated);
        $city->load('province', 'districts');

        return new CityResource($city);
    }

    public function show(string $id)
    {
        $city = City::with('province', 'districts')->findOrFail($id);
        return new CityResource($city);
    }

    public function update(CityRequest $request, string $id)
    {
        $validated = $request->validated();
        $validated['province_id'] = (int) $validated['province_id'];

        $city = City::findOrFail($id);
        $city->update($validated);
        $city->load('province', 'districts');

        return new CityResource($city);
    }

    public function destroy(string $id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Kota berhasil dihapus'
        ]);
    }
}