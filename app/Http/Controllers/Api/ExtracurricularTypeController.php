<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExtracurricularType;
use App\Http\Requests\ExtracurricularTypeRequest;
use Illuminate\Support\Facades\DB;

class ExtracurricularTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ExtracurricularTypeRequest $request)
    {
        $q = $request->q ?? null;
        $data = ExtracurricularType::select("*");
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
    public function store(ExtracurricularTypeRequest $request)
    {
        $data = $request->all();
        if ($data['id']) {
            unset($data['id']);
        }
        return response()->json([
            'data'  => ExtracurricularType::create($data),
            'msg'   =>'Data jenis ekstrakurikuler berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExtracurricularType $extracurricularType)
    {
        return response()->json(['data' => $extracurricularType], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExtracurricularTypeRequest $request, ExtracurricularType $extracurricularType)
    {
        $data = $request->all();
        foreach($data as $k => $v) {
            $extracurricularType->$k = $v;
        }
        $extracurricularType->save();
        return response()->json([
            'data'  => $extracurricularType,
            'msg'   => 'Data jenis ekstrakurikuler berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExtracurricularType $extracurricularType)
    {
        if (DB::table('extracurriculars')->where('type_id', $extracurricularType->id)->count()) {
            return response()->json([
                'msg' => 'Kategori ini masih menjadi referensi di data Ekstrakurikuler.!'
            ], 422);
        }
        $extracurricularType->delete();
        return response()->json([
            'data'  => $extracurricularType,
            'msg'   => 'Data jenis ekstrakurikuler berhasil dihapus',
        ], 200);
    }
}
