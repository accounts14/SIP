<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Http\Requests\TeacherRequest;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TeacherRequest $request)
    {
        // filter & pagination
        $page  = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $request->order ?? 'id';
        $ordtp = $request->orderType ?? 'asc';
        $q = $request->q ?? null;
        $param = '';

        $data = Teacher::select("*");

        if ($q) {
            $data->where(function($query) use ($q) {
                $query->where('nik', 'like', "%$q%")
                    ->orWhere('nama', 'like', "%$q%")
                    ->orWhere('nip', 'like', "%$q%")
                    ->orWhere('nuptk', 'like', "%$q%");
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
            'data'  => $data->get(),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl.$param,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeacherRequest $request)
    {
        $data = $request->all();
        if ($data['id']) {
            unset($data['id']);
        }
        return response()->json([
            'data'  => Teacher::create($data),
            'msg'   =>'Data Guru berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        return response()->json(['data' => $teacher], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeacherRequest $request, Teacher $teacher)
    {
        $data = $request->all();
        if (Teacher::where('id', $teacher->id)->update($data)) {
            return response()->json(['msg' =>'Data Guru berhasil diubah.'], 200);
        } else {
            return response()->json(['msg' =>'Data Guru TIDAK berhasil diubah.'], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return response()->json([
            'data'  => $teacher,
            'msg'   => 'Data Guru berhasil dihapus',
        ], 200);
    }
}
