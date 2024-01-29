<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Http\Requests\FacilityRequest;
use App\Http\Resources\FacilityResource;
use App\Models\Gallery;

class FacilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(FacilityRequest $request)
    {
        $q = $request->q ?? null;
        $data = Facility::with(['school']);
        if ($q) {
            $data->where('description', 'like', "%$q%");
        }
        return response()->json(['data' => FacilityResource::collection($data->get())]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FacilityRequest $request)
    {
        $data = [
            'school_id'   => $request->school_id,
            'type_id'     => $request->type_id,
            'description' => $request->description,
            'condition'   => $request->condition,
        ];
        $facility = Facility::create($data);

        if ($request->hasFile('files')) {
            $files = $request->file('files');
            foreach ($files as $file) {
                $fileName = "F-".time()."_".$file->getClientOriginalName();
                $file->move('facilities', $fileName);
                $data[] = [
                    'imageable_type' => 'App\Models\Facility',
                    'imageable_id'=> $facility->id,
                    'caption'     => 'Image of '.$fileName,
                    'description' => 'Description of '.$fileName,
                    'path'        => 'facilities/'.$fileName
                ];
            }
            Gallery::insert($data);
        }
        return response()->json([
            'data' => new FacilityResource($facility),
            'msg'  =>'Data fasilitas berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Facility $facility)
    {
        return response()->json([
            'data' => new FacilityResource($facility->with('images')->first())
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FacilityRequest $request, Facility $facility)
    {
        $data = $request->all();
        foreach($data as $k => $v) {
            $facility->$k = $v;
        }
        $facility->save();
        return response()->json([
            'data' => new FacilityResource($facility),
            'msg'  => 'Data fasilitas berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facility $facility)
    {
        $facility->delete();
        return response()->json([
            'data' => new FacilityResource($facility),
            'msg'  => 'Data fasilitas berhasil dihapus',
        ], 200);
    }
}
