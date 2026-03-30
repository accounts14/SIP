<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentRegistration;
use App\Http\Requests\StudentRegistrationRequest;
use App\Http\Resources\StudentRegistrationResource;

class StudentRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(StudentRegistrationRequest $request)
    {
        // filter & pagination
        $page  = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $request->order ?? 'id';
        $ordtp = $request->orderType ?? 'asc';
        $sch = $request->sch ?? null;
        $student = $request->student ?? null;
        $status = $request->status ?? null;
        $param = '';

        $data = StudentRegistration::select("*");

        // Jika role member: filter hanya registrasi miliknya sendiri (via user_members)
        if (auth()->user()->role === 'member') {
            $userMember = \DB::table('user_members')
                ->where('user_id', auth()->id())
                ->first();
            if ($userMember) {
                $data->where('student_id', $userMember->student_id);
            } else {
                $data->whereRaw('0=1'); // no data jika belum punya student record
            }
        }

        if ($status) {
            $param .= '&status'.$status;
            $data->where('status', $status);
        }

        if ($sch) {
            $param .= '&sch'.$sch;
            $data->where('school_id', $sch);
        }
        
        if ($student) {
            $param .= '&student'.$student;
            $data->where('student_id', $student);
        }
        $data->with(['school','student','regForm']);

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
            'data'  => StudentRegistrationResource::collection($data->get()),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => ($count >= $limit * $page) ? $nextPageUrl.$param : null,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentRegistrationRequest $request)
    {
        $data = $request->all();
        // Status default '0' (menunggu) jika tidak dikirim
        $data['status'] = $data['status'] ?? '0';
        return response()->json([
            'data'  => StudentRegistration::create($data),
            'msg'   =>'Registrasi berhasil.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentRegistration $registration)
    {
        return response()->json(['data' => $registration], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentRegistrationRequest $request, StudentRegistration $registration)
    {
        if ($request->school_id) {
            $registration->school_id = $request->school_id;
        }
        if ($request->student_id) {
            $registration->student_id = $request->student_id;
        }
        if ($request->registration_form_id) {

            $registration->registration_form_id = $request->registration_form_id;
        }
        if ($request->status) {
            $registration->status = $request->status;
        }
        if ($request->school_origin) {
            $registration->school_origin = $request->school_origin;
        }
        $registration->save();
        return response()->json([
            'data'  => $registration,
            'msg'   =>'Data registrasi berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentRegistration $registration)
    {
        $registration->delete();
        return response()->json([
            'data'  => $registration,
            'msg'   => 'Data registrasi berhasil dihapus',
        ], 200);
    }
}