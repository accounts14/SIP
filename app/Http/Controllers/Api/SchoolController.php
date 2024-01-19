<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\School;
use App\Models\User;
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

        $schools = School::select('*')->with('schoolLevels');
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
        $school = School::with('testimonies', 'schoolLevels')->findByIdOrSlug($identifier);
        return new SchoolResource($school);
    }

    public function store(SchoolRequest $request) {
        $data = $request->all();
        $data['slug']  = Str::slug($data['name']);
        $school             = School::create($data);
        $genUser = $this->genUser($school->id, $school);
        // return new SchoolResource($school);
        return response()->json([
            'data'  => new SchoolResource($school),
            'login' => $genUser,
            'msg'   => 'Data Sekolah berhasil dibuat',
        ], 200);
    }

    public function update(SchoolRequest $request, School $id) {
        $data = $request->all();
        $data['slug']  = Str::slug($data['name']);
        foreach($data as $k => $v) {
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
        // RegistrationForm, StudentRegistration, Testimony, User
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

    public function getNearestSchoolsByLocation(Request $request) {
        $locType = $request->get('location_type');
        $locId = $request->get('location_id');
        
        $filteredSchools = School::with('schoolLevels');
        
        switch ($locType) {
            case 'province':
                $filteredSchools->where('province_id', $locId);
                break;
            case 'city':
                $filteredSchools->where('city_id', $locId);
                break;
            case 'district':
                $filteredSchools->where('district_id', $locId);
                break;
            case 'subdistrict':
                $filteredSchools->where('subdistrict_id', $locId);
                break;
            
            default:
                break;
        }

        return SchoolResource::collection($filteredSchools->get());
    }

    public function genUser($id, $sch = null) {
        $count = User::where('school_id', $id)->count();
        if (!$count) {
            if ($sch == null) {
                $dsch = School::where('id', $id)->first();
            } else {
                $dsch = $sch;
            }
            $pw = Str::random(8);
            $email = str_replace('-', '', strtolower($dsch->slug)).'@mail.com';
            $data = [
                'uuid'  => Str::uuid(),
                'school_id' => $id,
                'name'      => $dsch->name,
                'email'     => $email,
                'password'  => bcrypt($pw),
                'role'      => 'superadmin',
            ];
            $lg = ['email' => $email, 'password' => $pw];
            if ($sch) {
                User::create($data);
                return $lg;
            }

            return response()->json([
                'data'  => User::create($data),
                'login' => $lg,
                'msg'   => 'Admin Sekolah berhasil dibuat',
            ], 200);
        } else {
            return response()->json([
                'message'=> 'Admin Sekolah sudah pernah dibuat sebelumnya.!',
            ], 422);
        }
    }
}
