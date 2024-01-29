<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubdistrictRequest;
use App\Http\Resources\SubdistrictResource;
use App\Models\SubDistrict;
use Illuminate\Http\Request;

class SubdistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', null);
        $subdistrict = SubDistrict::with('district', 'schools');
        if ($request->q) {
            $subdistrict->where('subdis_name', 'like', "%$request->q%");
        }
        if ($request->kec) {
            $subdistrict->where('dis_id', $request->kec);
        }
        return SubdistrictResource::collection($subdistrict->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubdistrictRequest $request)
    {
        $subdistrict = SubDistrict::create($request->all());
        $subdistrict->load('district', 'schools');
        return new SubdistrictResource($subdistrict);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $subdistrict = SubDistrict::findOrFail($id);
        if ($request->noLoad) {
            return new SubdistrictResource($subdistrict);
        }
        $subdistrict->load('district', 'schools');
        return new SubdistrictResource($subdistrict);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubdistrictRequest $request, string $id)
    {
        SubDistrict::where('id', $id)->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Data successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SubDistrict::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Data successfully deleted']);
    }
}
