<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $cities = City::with('districts');
        if ($request->q) {
            $cities->where('city_name', 'like', "%$request->q%");
        }
        if ($request->prov) {
            $cities->where('prov_id', $request->prov);
        }
        return CityResource::collection($cities->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request)
    {
        $city = City::create($request->all());
        $city->load('province', 'districts');
        return new CityResource($city);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $city = City::findOrFail($id);
        if ($request->noLoad) {
            return new CityResource($city);
        }
        $city->load('province', 'districts');
        return new CityResource($city);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, string $id)
    {
        City::where('id', $id)->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Data successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        City::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Data successfully deleted']);
    }
}
