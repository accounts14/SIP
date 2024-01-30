<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\DistrictRequest;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', null);
        $districts = District::with('subdistricts');
        if ($request->q) {
            $districts->where('dis_name', 'like', "%$request->q%");
        }
        if ($request->kab) {
            $districts->where('city_id', $request->kab);
        }
        if ($limit) {
            return DistrictResource::collection($districts->paginate($limit));
        }
        return DistrictResource::collection($districts->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DistrictRequest $request)
    {
        $district = District::create($request->all());
        $district->load('city', 'subdistricts');
        return new DistrictResource($district);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $district = District::findOrFail($id);
        if ($request->noLoad) {
            return new DistrictResource($district);
        }
        $district->load('city', 'subdistricts');
        return new DistrictResource($district);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DistrictRequest $request, string $id)
    {
        District::where('id', $id)->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Data successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        District::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Data successfully deleted']);
    }
}
