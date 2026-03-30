<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCandidate;
use App\Models\User;
use App\Http\Requests\UserCandidateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserCandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserCandidateRequest $request)
    {
        $page  = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $request->order ?? 'id';
        $ordtp = $request->orderType ?? 'asc';
        $q     = $request->q ?? null;
        $param = '';

        $data = UserCandidate::select('*');

        if ($q) {
            $data->where(function ($query) use ($q) {
                $query->where('nik',       'like', "%$q%")
                    ->orWhere('nama',      'like', "%$q%")
                    ->orWhere('nama_ayah', 'like', "%$q%")
                    ->orWhere('nama_ibu',  'like', "%$q%")
                    ->orWhere('nama_wali', 'like', "%$q%")
                    ->orWhere('nisn',      'like', "%$q%")
                    ->orWhere('alamat',    'like', "%$q%");
            });
            $param .= '&q=' . $q;
        }

        $count       = $data->count();
        $nextPageUrl = null;

        if ($count >= $limit * $page) {
            $nextPageUrl = preg_replace('/\?.*/i', '', $request->fullUrl()) . '?page=' . ((int) $page + 1);
            if (isset($request->limit))     $param .= '&limit='     . $limit;
            if ($order !== 'id')            $param .= '&order='     . $order;
            if (isset($request->orderType)) $param .= '&orderType=' . $ordtp;
        }

        return response()->json([
            'data'        => $data->offset($ofs)->limit($limit)->orderBy($order, $ordtp)->get(),
            'count'       => $count,
            'limit'       => $limit,
            'nextPageUrl' => $nextPageUrl ? $nextPageUrl . $param : null,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * Alur:
     * 1. Simpan data lengkap siswa ke user_candidates
     * 2. Buat akun login di tabel users (role=member)
     * 3. Hubungkan keduanya di user_members
     */
    public function store(UserCandidateRequest $request)
    {
        $data = $request->all();

        // Buang id jika ada (auto increment)
        unset($data['id']);

        // Buang password dari data candidate (bukan kolom di user_candidates)
        unset($data['password']);

        // Simpan data kandidat siswa
        $candidate = UserCandidate::create($data);

        // Cek apakah email sudah punya akun
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Buat akun login baru — role=member (satu-satunya yang valid untuk siswa)
            $user = User::create([
                'uuid'     => Str::uuid(),
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make('123456'),
                'role'     => 'member',
                // type & school_id dibiarkan null untuk siswa
            ]);
        }

        // Hubungkan user dengan candidate di user_members
        DB::table('user_members')->insert([
            'user_id'    => $user->id,
            'student_id' => $candidate->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'data' => $candidate,
            'msg'  => 'Data Siswa berhasil ditambah.',
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

        // Wilayah sudah berupa string dari form, tidak perlu encode JSON
        // kolom provinsi, kabupaten, kecamatan, kelurahan adalah varchar(100)

        if (UserCandidate::where('id', $student->id)->update($data)) {
            return response()->json(['msg' => 'Data Siswa berhasil diubah.'], 200);
        }

        return response()->json(['msg' => 'Data Siswa TIDAK berhasil diubah.'], 422);
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
            'data' => $student,
            'msg'  => 'Data Siswa berhasil dihapus',
        ], 200);
    }
}