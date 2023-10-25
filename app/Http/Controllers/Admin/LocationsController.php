<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use App\Models\SubDistrict;
use Illuminate\Http\Request;

class LocationsController extends Controller
{

    public function getByType(Request $request, $type)
    {
        switch ($type) {
            case 'province':
                $provinces = Province::paginate(20);
                return response()->json(['data' => $provinces], 200);

            case 'city':
                $query = City::query();
                if ($request->id) {
                    $query->where('prov_id', $request->id);
                }

                if ($request->name) {
                    $query->where('city_name', 'like', '%' . $request->name . '%');
                }

                $cities = $query->paginate(20);
                return response()->json(['data' => $cities], 200);

            case 'district':
                $query = District::query();
                if ($request->id) {
                    $query->where('city_id', $request->id);
                }

                if ($request->name) {
                    $query->where('dis_name', 'like', '%' . $request->name . '%');
                }

                $districts = $query->paginate(20);
                return response()->json(['data' => $districts], 200);

            case 'subdistrict':
                $query = Subdistrict::query();
                if ($request->id) {
                    $query->where('district_id', $request->id);
                }

                if ($request->name) {
                    $query->where('subdis_name', 'like', '%' . $request->name . '%');
                }

                $subdistricts = $query->paginate(20);
                return response()->json(['data' => $subdistricts], 200);

            default:
                return response()->json(['error' => 'Invalid location type'], 400);
        }
    }
}
