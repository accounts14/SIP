<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserRequest $request)
    {
        // filter & pagination
        $page  = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $request->order ?? 'id';
        $ordtp = $request->orderType ?? 'asc';
        $q = $request->q ?? null;
        $param = '';

        $data = User::select("*");

        if (!auth()->user()->school_id) {
    $data->with('school'); // superadmin lihat semua user
} else {
    $data->where('school_id', auth()->user()->school_id)->with('school');
}

        if ($q) {
            $data->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
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
        $data->offset($ofs)->limit($limit)->orderBy($order, $ordtp);
        return response()->json([
            'data'  => UserResource::collection($data->get()),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl.$param,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();

        if (isset($data['id'])) {
            unset($data['id']);
        }

        $data['uuid']     = Str::uuid();
        $data['password'] = bcrypt($data['password']);

        /*
         * FIX: Dulu school_id selalu di-override dengan school_id milik
         * user yang login (superadmin → NULL), sehingga school_id dari
         * request diabaikan.
         *
         * Sekarang:
         * - Jika yang login adalah superadmin (school_id = null), gunakan
         *   school_id yang dikirim dari request (boleh null juga).
         * - Jika yang login adalah admin sekolah (punya school_id), tetap
         *   pakai school_id miliknya (tidak boleh diubah oleh request).
         */
        $loggedInSchoolId = auth()->user()->school_id;

        if ($loggedInSchoolId) {
            // Admin sekolah hanya bisa buat user di sekolahnya sendiri
            $data['school_id'] = $loggedInSchoolId;
        } else {
            // Superadmin: pakai school_id dari request (bisa null atau ID sekolah tertentu)
            $data['school_id'] = isset($data['school_id']) ? $data['school_id'] : null;
        }

        /*
         * FIX: type juga harus diambil dari request.
         * Pastikan nilainya valid sesuai enum di DB: 'sip', 'school_admin', 'school_head'
         * Jika tidak dikirim atau tidak valid, set null.
         */
        $allowedTypes = ['sip', 'school_admin', 'school_head'];
        if (!isset($data['type']) || !in_array($data['type'], $allowedTypes)) {
            $data['type'] = null;
        }

        /*
         * FIX: role hanya boleh diset oleh superadmin.
         * Admin biasa tidak boleh membuat user dengan role superadmin.
         */
        // AUTO-ENFORCE: type memaksa role
if (in_array($data['type'], ['school_head', 'sip'])) {
    $data['role'] = 'superadmin';  // ← paksa superadmin
} elseif ($data['type'] === 'school_admin') {
    $data['role'] = 'admin';
}

        $user = User::create($data);

        return response()->json([
            'data' => new UserResource($user),
            'msg'  => 'Data Admin berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $identifier)
    {
        $user = User::where('id', $identifier)->orWhere('uuid', $identifier)->first();
        return response()->json(['data' => $user], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();

        // Handle password
        if (isset($data['password']) && $data['password'] != '') {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        /*
         * FIX: Sama seperti store — jangan selalu override school_id
         * dengan school_id milik user yang login.
         */
        $loggedInSchoolId = auth()->user()->school_id;

        if ($loggedInSchoolId) {
            // Admin sekolah hanya bisa update user di sekolahnya sendiri
            $data['school_id'] = $loggedInSchoolId;
        } else {
            // Superadmin: gunakan school_id dari request
            // Jika request kirim null, berarti lepas dari sekolah
            if (array_key_exists('school_id', $data)) {
                $data['school_id'] = $data['school_id'] ?: null;
            }
            // Jika request tidak kirim school_id sama sekali, jangan ubah
        }

        /*
         * FIX: type juga harus diambil dari request saat update.
         */
        $allowedTypes = ['sip', 'school_admin', 'school_head'];
        if (array_key_exists('type', $data)) {
            if (!in_array($data['type'], $allowedTypes)) {
                $data['type'] = null;
            }
        }

        // Hanya superadmin yang boleh mengubah role
        if (auth()->user()->role !== 'superadmin') {
            if (isset($data['role'])) {
                unset($data['role']);
            }
        }

        if (User::where('id', $user->id)->update($data)) {
            return response()->json(['msg' => 'Data Admin berhasil diubah.'], 200);
        } else {
            return response()->json(['msg' => 'Data Admin TIDAK berhasil diubah.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'data' => $user,
            'msg'  => 'Data Admin berhasil dihapus',
        ], 200);
    }

    public function uploadAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->getSize() > 1024000) {
                return response()->json(['msg' => 'Ukuran file terlalu besar (max 1 mb)'], 422);
            }
            if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                $fileName = "A-" . time() . "_" . str_replace('+', '_', $file->getClientOriginalName());
                $path = $file->move('storage/avatar', $fileName);

                User::where('id', $request->user()->id)->update(['avatar' => $path]);
                return response()->json(['msg' => 'Avatar berhasil diubah.'], 200);
            } else {
                return response()->json(['msg' => 'Format file hanya boleh (jpg, jpeg & png).!'], 422);
            }
        }
        return response()->json(['msg' => 'Tidak ada file ditemukan.!'], 422);
    }
}