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
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index(Request $req)
    {
        // filter & pagination
        $page  = $req->page ?? 1;
        $limit = $req->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $req->order ?? 'id';
        $ordtp = $req->orderType ?? 'asc';
        $q = $req->q ?? null;
        $param = '';

        $schools = School::select('*');
        if ($q) {
            $schools->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('npsn', 'like', "%$q%")
                    ->orWhere('headmaster', 'like', "%$q%")
                    ->orWhere('location', 'like', "%$q%");
            });
            $param .= '&q='.$q;
        }
        $count = $schools->count();
        $nextPageUrl = null;
        if ($count >= $limit * $page) {
            $nextPageUrl = preg_replace('/\?.*/i', '', $req->fullUrl()) . '?page=' . ((int)$page + 1);
            if (isset($req->limit)) {
                $param .= '&limit='.$limit;
            }
            if ($order !== 'id') {
                $param .= '&order='.$order;
            }
            if (isset($req->orderType)) {
                $param .= '&orderType='.$ordtp;
            }
        }

        return response()->json([
            'data'  => SchoolResource::collection($schools->offset($ofs)->limit($limit)->orderBy($order, $ordtp)->get()),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl.$param,
        ], 200);
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

    public function update(SchoolRequest $request, School $id) {
        $validated          = $request->validated();
        $validated['slug']  = Str::slug($validated['name']);
        foreach($validated as $k => $v) {
            $id->$k = $v;
        }
        // $school             = School::where('id', $id)->update($validated);
        $id->save();
        return response()->json([
            'data'  => $id,
            'msg'   => 'Data Sekolah berhasil diubah',
        ], 200);
    }

    public function destroy(School $id)
    {
        $id->delete();
        return response()->json([
            'data'  => $id,
            'msg'   => 'Data Sekolah berhasil dihapus',
        ], 200);
    }

    public function getNearestSchools(Request $request) {
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
