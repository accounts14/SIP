<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProvinceRequest;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $provinces = Province::with('cities');
        if ($request->q) {
            $provinces->where('prov_name', 'like', "%$request->q%");
        }
        return ProvinceResource::collection($provinces->paginate($limit));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProvinceRequest $request)
    {
        $validated = $request->validated();
        $prov = Province::create($validated);
        return response()->json($prov);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $prov = Province::findOrFail($id);
        return new ProvinceResource($prov);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProvinceRequest $request, string $id)
    {
        $validated = $request->validated();
        Province::where('id', $id)->update($validated);
        return response()->json(['status' => 'success', 'message' => 'Data successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Province::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Data successfully deleted']);
    }
}
