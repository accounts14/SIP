<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::all();
        return SchoolResource::collection($schools);
    }

    public function show($identifier)
    {
        $school = School::with('testimonies', 'schoolLevels')->findByIdOrSlug($identifier);
        return new SchoolResource($school);
    }

    public function store(SchoolRequest $request) {
        $validated          = $request->validated();
        $validated['slug']  = Str::slug($validated['name']);
        $school             = School::create($validated);
        return new SchoolResource($school);
    }

    public function update(SchoolRequest $request, string $id) {
        School::where('id', $id)->update($request->all());
        return response()->json(['status' => 'success', 'message' => 'Data successfully updated']);
    }

    public function delete(string $id) {
        School::where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Data successfully deleted']);
    }

    public function getNearestSchoolsByCoord(Request $request) {
        $userLatitude = $request->get('latitude');
        $userLongitude = $request->get('longitude');

        $distance = 10;

        $nearestSchools = School::select('*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$userLatitude, $userLongitude, $userLatitude]
            )
            ->having('distance', '<=', $distance)
            ->orderBy('distance')
            ->get();

        return response()->json(['nearestSchools' => $nearestSchools]);
    }

    public function getNearestSchoolsByLocation(Request $request) {
        $userLatitude = $request->get('latitude');
        $userLongitude = $request->get('longitude');

        $distance = 10;

        $nearestSchools = School::select('*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$userLatitude, $userLongitude, $userLatitude]
            )
            ->having('distance', '<=', $distance)
            ->orderBy('distance')
            ->get();

        return response()->json(['nearestSchools' => $nearestSchools]);
    }
}
