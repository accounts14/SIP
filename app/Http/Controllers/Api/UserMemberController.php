<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserMemberRequest;
use App\Http\Resources\UserMemberResource;
use App\Models\UserMember;

class UserMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserMemberRequest $request)
    {
        // filter & pagination
        $page  = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $request->order ?? 'id';
        $ordtp = $request->orderType ?? 'asc';

        $data = UserMember::select("*");

        $count = $data->count();
        $nextPageUrl = null;
        if ($count >= $limit * $page) {
            $nextPageUrl = preg_replace('/\?.*/i', '', $request->fullUrl()) . '?page=' . ((int)$page + 1);
            if (isset($request->limit)) {
                $nextPageUrl .= '&limit='.$limit;
            }
            if ($order !== 'id') {
                $nextPageUrl .= '&order='.$order;
            }
            if (isset($request->orderType)) {
                $nextPageUrl .= '&orderType='.$ordtp;
            }
        }

        $data->offset($ofs)->limit($limit)->orderBy($order, $ordtp);
        return response()->json([
            'data'  => UserMemberResource::collection($data->get()),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserMemberRequest $request)
    {
        $data = $request->all();
        if (isset($data['id'])) {
            unset($data['id']);
        }
        return response()->json([
            'data'  => new UserMemberResource(UserMember::create($data)),
            'msg'   =>'Data Member berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(UserMember $user_member)
    {
        return response()->json(['data' => new UserMemberResource($user_member)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserMemberRequest $request, UserMember $user_member)
    {
        $data = $request->all();
        if (UserMember::where('id', $user_member->id)->update($data)) {
            return response()->json(['msg' =>'Data Member berhasil diubah.'], 200);
        } else {
            return response()->json(['msg' =>'Data Member TIDAK berhasil diubah.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserMember $user_member)
    {
        $user_member->delete();
        return response()->json([
            'data'  => $user_member,
            'msg'   => 'Data Member berhasil dihapus',
        ], 200);
    }
}
