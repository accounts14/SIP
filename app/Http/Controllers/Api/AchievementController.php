<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Http\Requests\AchievementRequest;
use App\Http\Resources\AchievementResource;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $data = Achievement::select('*')->with('school');
        if ($q) {
            $data->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('achieved_from', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
            });
            $param .= '&q='.$q;
        }
        $count = $data->count();
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

        $data->offset($ofs)->limit($limit)->orderBy($order, $ordtp)->get();
        return response()->json([
            'data'  => AchievementResource::collection($data),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl.$param,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AchievementRequest $request)
    {
        $data = $request->all();
        if ($data['id']) {
            unset($data['id']);
        }
        return response()->json([
            'data' => new AchievementResource(Achievement::create($data)),
            'msg'  =>'Data Penghargaan berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Achievement $achievement)
    {
        return response()->json(['data' => new AchievementResource($achievement)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AchievementRequest $request, Achievement $achievement)
    {
        $data = $request->all();
        foreach($data as $k => $v) {
            $achievement->$k = $v;
        }
        $achievement->save();
        return response()->json([
            'data' => new AchievementResource($achievement),
            'msg'  => 'Data Penghargaan berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Achievement $achievement)
    {
        $achievement->delete();
        return response()->json([
            'data' => new AchievementResource($achievement),
            'msg'  => 'Data Penghargaan berhasil dihapus',
        ], 200);
    }
}
