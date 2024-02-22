<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCandidate;
use App\Http\Requests\UserCandidateRequest;
use Illuminate\Support\Facades\DB;

class UserCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserCandidateRequest $request)
    {
        // filter & pagination
        $page  = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $request->order ?? 'id';
        $ordtp = $request->orderType ?? 'asc';
        $q = $request->q ?? null;
        $param = '';

        $data = UserCandidate::select("*");

        if ($q) {
            $data->where(function($query) use ($q) {
                $query->where('nik', 'like', "%$q%")
                    ->orWhere('nama', 'like', "%$q%")
                    ->orWhere('nama_ayah', 'like', "%$q%")
                    ->orWhere('nama_ibu', 'like', "%$q%")
                    ->orWhere('nama_wali', 'like', "%$q%")
                    ->orWhere('nisn', 'like', "%$q%")
                    ->orWhere('alamat', 'like', "%$q%");
            });
            $param .= '&q='.$q;
        }

        $count = $data->count();
        $nextPageUrl = null;
        if ($count >= $limit * $page) {
            $nextPageUrl = preg_replace('/\?.*/i', '', $request->fullUrl()) . '?page=' . ((int)$page + 1);
            if (isset($request->limit)) {
                $param .= '&limit='.$limit;
            }
            if ($order !== 'id') {
                $param .= '&order='.$order;
            }
            if (isset($request->orderType)) {
                $param .= '&orderType='.$ordtp;
            }
        }

        return response()->json([
            'data'  => $data->offset($ofs)->limit($limit)->orderBy($order, $ordtp)->get(),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl.$param,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserCandidateRequest $request)
    {
        $data = $request->all();
        if (isset($data['id'])) {
            unset($data['id']);
        }
        if (isset($data['provinsi']['id'])) {
            $data['provinsi'] = json_encode($data['provinsi']);
        }
        if (isset($data['kabupaten']['id'])) {
            $data['kabupaten'] = json_encode($data['kabupaten']);
        }
        if (isset($data['kecamatan']['id'])) {
            $data['kecamatan'] = json_encode($data['kecamatan']);
        }
        if (isset($data['kelurahan']['id'])) {
            $data['kelurahan'] = json_encode($data['kelurahan']);
        }
        return response()->json([
            'data'  => UserCandidate::create($data),
            'msg'   =>'Data Siswa berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserCandidate $student)
    {
        return response()->json(['data' => $student], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserCandidateRequest $request, UserCandidate $student)
    {
        $data = $request->all();
        if (isset($data['provinsi']['id'])) {
            $data['provinsi'] = json_encode($data['provinsi']);
        }
        if (isset($data['kabupaten']['id'])) {
            $data['kabupaten'] = json_encode($data['kabupaten']);
        }
        if (isset($data['kecamatan']['id'])) {
            $data['kecamatan'] = json_encode($data['kecamatan']);
        }
        if (isset($data['kelurahan']['id'])) {
            $data['kelurahan'] = json_encode($data['kelurahan']);
        }
        if (UserCandidate::where('id', $student->id)->update($data)) {
            return response()->json(['msg' =>'Data Siswa berhasil diubah.'], 200);
        } else {
            return response()->json(['msg' =>'Data Siswa TIDAK berhasil diubah.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserCandidate $student)
    {
        if (DB::table('student_registrations')->where('student_id', $student->id)->count()) {
            return response()->json([
                'msg' => 'Data Siswa ini masih menjadi referensi di data Registrasi.!'
            ], 422);
        }
        if (DB::table('user_members')->where('student_id', $student->id)->count()) {
            return response()->json([
                'msg' => 'Data Siswa ini masih menjadi referensi di data lain.!'
            ], 422);
        }
        $student->delete();
        return response()->json([
            'data'  => $student,
            'msg'   => 'Data Siswa berhasil dihapus',
        ], 200);
    }
}
