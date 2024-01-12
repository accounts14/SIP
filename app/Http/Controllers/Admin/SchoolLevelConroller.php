<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolLevelRequest;
use App\Http\Resources\SchoolLevelResource;
use App\Http\Resources\SchoolResource;
use App\Models\SchoolLevel;
use Illuminate\Http\Request;

class SchoolLevelConroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schoolLevels = SchoolLevel::all();
        return SchoolLevelResource::collection($schoolLevels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SchoolLevelRequest $request)
    {
        $schoolLevel = SchoolLevel::create($request->all());
        return new SchoolLevelResource($schoolLevel);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schoolLevel = SchoolLevel::findOrFail($id);
        $schoolLevel->load('schools');
        return new SchoolLevelResource($schoolLevel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SchoolLevelRequest $request, string $id)
    {
        SchoolLevel::where('id', $id)->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Data successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SchoolLevel::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Data successfully deleted']);
    }
}
