<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\UserRequest;

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
    public function store(UserRequest $request)
    {
        $data = $request->all();
        if ($data['id']) {
            unset($data['id']);
        }
        $data['password'] = bcrypt($data['provinsi']);
        return response()->json([
            'data'  => User::create($data),
            'msg'   =>'Data Admin berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(['data' => $user], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        if ($data['password']) {
            $data['password'] = bcrypt($data['provinsi']);
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
}
