<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        $schoolsWithSlug = $schools->map(function ($school) {
            $school['slug'] = Str::slug($school['name']);
            return $school;
        });
        return response()->json(['data' => $schoolsWithSlug]);
    }

    public function show($identifier)
    {
        if (is_numeric($identifier)) {
            $school = School::find($identifier);
        } else {
            $school = School::where('slug', $identifier)->first();
        }

        if (!$school) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $testimonies = Testimony::where('school_id', $school->id)->get();

        return response()->json(['school_data' => $school, 'testimonies' => $testimonies], 200);
    }
}
