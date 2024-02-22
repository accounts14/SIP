<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Http\Requests\AgendaRequest;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(AgendaRequest $request)
    {
        // filter & pagination
        $page  = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $request->order ?? 'event_date';
        $ordtp = $request->orderType ?? 'asc';
        $q = $request->q ?? null;
        $status = $request->status ?? 1;
        $date = $request->date ?? null;
        $param = '';

        $data = Agenda::where('status', $status);
        if ($q) {
            $data->where(function($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                    ->orWhere('content', 'like', "%$q%")
                    ->orWhere('tags', 'like', "%$q%");
            });
            $param .= '&q='.$q;
        }
        // set filter if non member
        if (!$request->user()) {
            $data->where('for_member', 0);
        }
        // filter just draf if not school admin
        if (!$date) {
            $data->where('event_date', '>=', date('Y-m-d'));
        } else {
            $param .= '&date='.$date;
        }
        if ($request->has('schoolId')) {
            $data->where('school_id', $request->schoolId);
            $param .= '&schoolId='.$request->schoolId;
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
            'nextPageUrl' => $nextPageUrl ? $nextPageUrl.$param : null,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgendaRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($data['activity']);
        $data['school_id'] = $request->user()->school_id;
        if (isset($data['id'])) {
            unset($data['id']);
        }
        return response()->json([
            'data' => Agenda::create($data),
            'msg'  =>'Agenda baru berhasil dibuat' . ($data['status'] ? ' dan dipublikasikan.' : '.'),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Agenda $agenda)
    {
        return response()->json(['data' => $agenda->with('school')->first()], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AgendaRequest $request, Agenda $agenda)
    {
        $data = $request->all();
        foreach($data as $k => $v) {
            $agenda->$k = $v;
        }
        if ($data['activity']) {
            $agenda->slug = Str::slug($agenda->activity);
        }
        $agenda->school_id = $request->user()->school_id;

        $agenda->save();
        return response()->json([
            'data' => $agenda,
            'msg'  =>'Agenda berhasil diubah' . ($request->status ? ' dan dipublikasikan.' : ' dan dinonaktifkan.'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Agenda $agenda)
    {
        $agenda->delete();
        return response()->json([
            'data'  => $agenda,
            'msg'   => 'Agenda berhasil dihapus',
        ], 200);
    }

    public function bySchool($schID, AgendaRequest $request) {
        $limit = $request->get('limit', 10);
        $data = Agenda::where('school_id', $schID)->with('school');
        if ($request->q) {
            $data->where('activity', 'like', "%$request->q%");
        }
        return $data->pagination($limit);
    }
}
