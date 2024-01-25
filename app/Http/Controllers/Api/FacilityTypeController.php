<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FacilityType;
use App\Http\Requests\FacilityTypeRequest;

class FacilityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => FacilityType::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FacilityTypeRequest $request)
    {
        $data = $request->all();
        if ($data['id']) {
            unset($data['id']);
        }
        return response()->json([
            'data'  => FacilityType::create($data),
            'msg'   =>'Data jenis fasilitas berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(FacilityType $facilityType)
    {
        return response()->json(['data' => $facilityType], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FacilityTypeRequest $request, FacilityType $facilityType)
    {
        $data = $request->all();
        foreach($data as $k => $v) {
            $facilityType->$k = $v;
        }
        $facilityType->save();
        return response()->json([
            'data'  => $facilityType,
            'msg'   => 'Data jenis fasilitas berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacilityType $facilityType)
    {
        $facilityType->delete();
        return response()->json([
            'data'  => $facilityType,
            'msg'   => 'Data jenis fasilitas berhasil dihapus',
        ], 200);
    }
}
