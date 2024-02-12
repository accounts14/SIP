<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Http\Requests\FacilityRequest;
use App\Http\Resources\FacilityResource;
use App\Models\Gallery;
use Illuminate\Http\Request;

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
        if ($request->has('schoolId')) {
            $data->where('school_id', $request->schoolId);
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

        if ($request->hasFile('images')) {
            $this->doUpload($request->file('images'), $facility);
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
        if ($data['type_id']) {
            $facility->type_id = $data['type_id'];
        }
        if ($data['description']) {
            $facility->description = $data['description'];
        }
        if ($data['condition']) {
            $facility->condition = $data['condition'];
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

    public function uploadImg(Request $request, Facility $id) {
        if ($request->hasFile('images')) {
            $this->doUpload($request->file('images'), $id);
            return response()->json([
                'msg'  => 'Gambar berhasil diupload',
            ], 200);
        }
    }

    private function doUpload($files, $facility) {
        foreach ($files as $file) {
            $fileName = "F-".time()."_".str_replace('+', '_', $file->getClientOriginalName());
            $path = $file->move('storage/facilities', $fileName);
            $img[] = [
                'imageable_type' => 'App\Models\Facility',
                'imageable_id'=> $facility->id,
                'caption'     => 'Caption of facility',
                'description' => 'Description for image '.$fileName,
                'path'        => $path,
                'school_id'   => $facility->school_id,
            ];
        }
        if (count($img)) {
            Gallery::insert($img);
        }
    }

    public function bySchool($schID)
    {
        $data = Facility::where('school_id', $schID);
        return response()->json(['data' => FacilityResource::collection($data->get())]);
    }
}
