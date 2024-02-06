<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Http\Requests\ExtracurricularRequest;
use App\Http\Resources\ExtracurricularResource;

class ExtracurricularController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ExtracurricularRequest $request)
    {
        $q = $request->q ?? null;
        $data = Extracurricular::with('school');
        if ($q) {
            $data->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('instructors', 'like', "%$q%");
            });
        }
        if ($request->has('schoolId')) {
            $data->where('school_id', $request->schoolId);
        }
        return response()->json(['data' => ExtracurricularResource::collection($data->get())]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExtracurricularRequest $request)
    {
        $data = $request->all();
        if ($data['id']) {
            unset($data['id']);
        }
        return response()->json([
            'data' => new ExtracurricularResource(Extracurricular::create($data)),
            'msg'  =>'Data Ekstrakurikuler berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Extracurricular $extracurricular)
    {
        return response()->json(['data' => new ExtracurricularResource($extracurricular)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExtracurricularRequest $request, Extracurricular $extracurricular)
    {
        $data = $request->all();
        foreach($data as $k => $v) {
            $extracurricular->$k = $v;
        }
        $extracurricular->save();
        return response()->json([
            'data' => new ExtracurricularResource($extracurricular),
            'msg'  => 'Data Ekstrakurikuler berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Extracurricular $extracurricular)
    {
        $extracurricular->delete();
        return response()->json([
            'data' => new ExtracurricularResource($extracurricular),
            'msg'  => 'Data Ekstrakurikuler berhasil dihapus',
        ], 200);
    }
}
