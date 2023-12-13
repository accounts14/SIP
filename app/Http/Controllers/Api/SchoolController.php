<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Models\Testimony;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
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
        $school = School::with('testimonies')->findByIdOrSlug($identifier);
        return new SchoolResource($school);
    }

    public function store(SchoolRequest $request) {
        $validated          = $request->validated();
        $validated['slug']  = Str::slug($validated['name']);
        $school             = School::create($validated);
        return new SchoolResource($school);
    }
}
