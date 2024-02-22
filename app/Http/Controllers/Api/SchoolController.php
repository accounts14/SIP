<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SchoolRequest;
use App\Http\Resources\SchoolResource;
use App\Models\Achievement;
use App\Models\Extracurricular;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\RegistrationForm;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Testimony;
use App\Models\User;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchoolController extends Controller
{
    public function index(Request $req)
    {
        // filter & pagination
        $page  = $req->page ?? 1;
        $limit = $req->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $req->order ?? 'is_member';
        $ordtp = $req->orderType ?? 'desc';
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
        if ($req->has('level')) {
            $schools->where('level', $req->level);
            $param .= '&level='.$req->level;
        }
        if ($req->has('prov')) {
            $schools->where('province_id', $req->prov);
            $param .= '&prov='.$req->prov;
        }
        if ($req->has('kab')) {
            $schools->where('city_id', $req->kab);
            $param .= '&kab='.$req->kab;
        }
        if ($req->has('kec')) {
            $schools->where('district_id', $req->kec);
            $param .= '&kec='.$req->kec;
        }
        if ($req->has('kel')) {
            $schools->where('subdistrict_id', $req->kel);
            $param .= '&kel='.$req->kel;
        }
        $count = $schools->count();
        $nextPageUrl = null;
        if ($count > $limit * $page) {
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

        $schools->offset($ofs)->limit($limit)->orderBy($order, $ordtp);
        return response()->json([
            'data'  => SchoolResource::collection($schools->get()),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl ? $nextPageUrl.$param : null,
        ], 200);
    }

    public function show($identifier)
    {
        $school = School::with([
            // 'testimonies',
            'schoolLevels',
            'facilities',
            'extracurriculars',
            'achievements',
            'superadmin'
        ])->withCount('teachers')->findByIdOrSlug($identifier);
        return new SchoolResource($school);
    }

    public function store(SchoolRequest $request) {
        $data = $request->all();
        $data['slug']  = Str::slug($data['name']);
        $school             = School::create($data);
        $genUser = $this->genUser($school->id, $school);
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
        $id->save();
        return response()->json([
            'data'  => $id,
            'msg'   => 'Data Sekolah berhasil diubah',
        ], 200);
    }

    public function destroy(School $id)
    {
        // RegistrationForm, StudentRegistration, Testimony, User
        if (User::where('school_id', $id->id)->count()) {
            return response()->json([
                'msg' => 'Data Sekolah ini masih menjadi referensi di data User.!'
            ], 422);
        }
        if (Achievement::where('school_id', $id->id)->count()) {
            return response()->json([
                'msg' => 'Data Sekolah ini masih menjadi referensi di data Penghargaan.!'
            ], 422);
        }
        if (Facility::where('school_id', $id->id)->count()) {
            return response()->json([
                'msg' => 'Data Sekolah ini masih menjadi referensi di data Fasilitas.!'
            ], 422);
        }
        if (Extracurricular::where('school_id', $id->id)->count()) {
            return response()->json([
                'msg' => 'Data Sekolah ini masih menjadi referensi di data Ekstrakurikuler.!'
            ], 422);
        }
        if (Gallery::where('school_id', $id->id)->count()) {
            return response()->json([
                'msg' => 'Data Sekolah ini masih menjadi referensi di data Galeri.!'
            ], 422);
        }
        if (RegistrationForm::where('school_id', $id->id)->count()) {
            return response()->json([
                'msg' => 'Data Sekolah ini masih menjadi referensi di data Form Registrasi.!'
            ], 422);
        }
        if (Teacher::where('school_id', $id->id)->count()) {
            return response()->json([
                'msg' => 'Data Sekolah ini masih menjadi referensi di data Guru.!'
            ], 422);
        }
        if (Testimony::where('school_id', $id->id)->count()) {
            return response()->json([
                'msg' => 'Data Sekolah ini masih menjadi referensi di data Testimoni.!'
            ], 422);
        }
        $id->delete();
        return response()->json([
            'data'  => $id,
            'msg'   => 'Data Sekolah berhasil dihapus',
        ], 200);
    }

    public function getNearestSchoolsByCoord(Request $request) {
        $userLatitude = $request->get('latitude');
        $userLongitude = $request->get('longitude');

        $distance = $request->query->get('distance') ?? 10;

        $nearestSchools = School::select('*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$userLatitude, $userLongitude, $userLatitude]
            )
            ->having('distance', '<=', $distance)
            ->orderBy('distance');

        $query = strtolower($request->query->get('query'));
        if ($query) {
            $nearestSchools->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($query).'%']);
        }

        $level = $request->query->get('level'); 
        if ($level) {
            $nearestSchools->where('level', $level);
        }
    
        $type = $request->query->get('type'); 
        if ($type) {
            $nearestSchools->whereRaw('LOWER(type) = ?', [strtolower($type)]);
        }
    
        $queryParams = $request->query();
        $data = $nearestSchools->paginate(10);
        $data->appends($queryParams);
        return SchoolResource::collection($data);
    }

    public function getNearestSchoolsByLocation(Request $request) {
        $locType = $request->get('location_type');
        $locId = $request->get('location_id');
        
        $filteredSchools = School::with('schoolLevels');
    
        $query = strtolower($request->query->get('query'));
        if ($query) {
            $filteredSchools->whereRaw('LOWER(name) LIKE ?', ['%'.strtolower($query).'%']);
        }

        $level = $request->query->get('level'); 
        if ($level) {
            $filteredSchools->where('level', $level);
        }
    
        $type = $request->query->get('type'); 
        if ($type) {
            $filteredSchools->whereRaw('LOWER(type) = ?', [strtolower($type)]);
        }

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

        $queryParams = $request->query();
        $data = $filteredSchools->paginate(10);
        $data->appends($queryParams);
        return SchoolResource::collection($data);
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
