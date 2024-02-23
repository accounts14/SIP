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
            $type = $request->type ?? 'app';
            if ($type == 'app') {
                $data->where('school_id', null);
            } else {
                $data->where('school_id', '!=', null)->with('school');
            }
        } else {
            $data->with('school');
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
        $data['uuid'] = Str::uuid();
        $data['password'] = bcrypt($data['password']);
        $data['school_id'] = auth()->user()->school_id;
        return response()->json([
            'data'  => User::create($data),
            'msg'   =>'Data Admin berhasil ditambah.',
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
        if (isset($data['password']) && $data['password'] != '') {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        $data['school_id'] = auth()->user()->school_id;
        if (auth()->user()->role != 'superadmin') {
            if (isset($data['role'])) {
                unset($data['role']);
            }
        }
        if (User::where('id', $user->id)->update($data)) {
            return response()->json(['msg' =>'Data Admin berhasil diubah.'], 200);
        } else {
            return response()->json(['msg' =>'Data Admin TIDAK berhasil diubah.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'data'  => $user,
            'msg'   => 'Data Admin berhasil dihapus',
        ], 200);
    }

    public function uploadAvatar(Request $request) {
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            if ($file->getSize() > 1024000) {
                return response()->json(['msg'  =>'Ukuran file terlalu besar (max 1 mb)'], 422);
            }
            if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                $fileName = "A-".time()."_".str_replace('+', '_', $file->getClientOriginalName());
                $path = $file->move('storage/avatar', $fileName);
                
                User::where('id', $request->user()->id)->update(['avatar' => $path]);
                return response()->json(['msg'  =>'Avatar berhasil diubah.'], 200);
            } else {
                return response()->json(['msg'  =>'Format file hanya boleh (jpg, jpeg & png).!'], 422);
            }
        }
        return response()->json(['msg'  =>'Tidak ada file ditemukan.!'], 422);
    }
}
