<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FacilityType;
use App\Http\Requests\FacilityTypeRequest;
use Illuminate\Support\Facades\DB;

class FacilityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FacilityTypeRequest $request)
    {
        $q = $request->q ?? null;
        $data = FacilityType::select("*");
        if ($q) {
            $data->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
            });
        }
        return response()->json(['data' => $data->get()]);
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
        if (DB::table('facilities')->where('type_id', $facilityType->id)->count()) {
            return response()->json([
                'msg' => 'Kategori ini masih menjadi referensi di data Fasilitas.!'
            ], 422);
        }
        $facilityType->delete();
        return response()->json([
            'data'  => $facilityType,
            'msg'   => 'Data jenis fasilitas berhasil dihapus',
        ], 200);
    }
}
