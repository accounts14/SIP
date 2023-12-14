<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegencyRequest;
use App\Http\Resources\RegencyResource;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regencies = Regency::with('districts')->get();
        return RegencyResource::collection($regencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegencyRequest $request)
    {
        $regency = Regency::create($request->all());
        $regency->load('province');
        return new RegencyResource($regency);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $regency = Regency::findOrFail($id);
        $regency->load('province', 'cities');
        return new RegencyResource($regency);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegencyRequest $request, string $id)
    {
        Regency::where('id', $id)->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Data successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Regency::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Data successfully deleted']);
    }
}
