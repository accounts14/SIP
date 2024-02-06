<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RegistrationForm;
use App\Http\Requests\RegistrationFormRequest;
use App\Http\Resources\RegistrationFormResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RegistrationFormRequest $request)
    {
        // filter & pagination
        $page  = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $request->order ?? 'id';
        $ordtp = $request->orderType ?? 'asc';
        $q = $request->q ?? null;
        $sch = $request->sch ?? null;
        $schId = $request->schId ?? null;
        $ta = $request->ta ?? null;
        $status = $request->status ?? '1';
        $param = '';

        if ($request->sch) {
            $data = RegistrationForm::select('registration_forms.*')
                ->join('schools', 'registration_forms.school_id', '=', 'schools.id');
        } else {
            $data = RegistrationForm::select('*');
        }
        $data->where('status', $status);

        if ($q) {
            $param .= '&q'.$q;
            if ($sch) {
                $data->where(function($query) use ($q) {
                    $query->where('title', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%")
                    ->orWhere('schools.name', 'like', "%$q%")
                    ->orWhere('schools.location', 'like', "%$q%")
                    ->orWhere('schools.headmaster', 'like', "%$q%")
                    ->orWhere('schools.npsn', 'like', "%$q%");
                });
            }else {
                $data->where(function($query) use ($q) {
                    $query->where('title', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
                });
            }
        }
        
        if ($schId) {
            $param .= '&schId'.$schId;
            $data->where('school_id', $schId);
        }
        
        if ($ta) {
            $param .= '&ta'.$ta;
            $data->where('ta', $ta);
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
            'data'  => RegistrationFormResource::collection($data->get()),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl.$param,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegistrationFormRequest $request)
    {
        $data = $request->all();
        $data['registration_field'] = json_encode($data['registration_field']);
        return response()->json([
            'data'  => RegistrationForm::create($data),
            'msg'   =>'Form Registrasi berhasil dibuat.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(RegistrationForm $registration_form)
    {
        return response()->json([
            'data' => new RegistrationFormResource($registration_form),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegistrationFormRequest $request, RegistrationForm $registration_form)
    {
        if ($request->school_id) {
            $registration_form->school_id = $request->school_id;
        }
        if ($request->ta) {
            $registration_form->ta = $request->ta;
        }
        if ($request->title) {
            $registration_form->title = $request->title;
        }
        if ($request->description) {
            $registration_form->description = $request->description;
        }
        if ($request->quota) {
            $registration_form->quota = $request->quota;
        }
        if ($request->registration_fee) {
            $registration_form->registration_fee = $request->registration_fee;
        }
        if ($request->registration_field) {
            $registration_form->registration_field = json_encode($request->registration_field);
        }
        if ($request->status) {
            $registration_form->status = $request->status;
        }
        $registration_form->save();
        return response()->json([
            'data'  => $registration_form,
            'msg'   =>'Form registrasi berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistrationForm $registration_form)
    {
        if (DB::table('student_registrations')->where('registration_form_id', $registration_form->id)->count()) {
            return response()->json([
                'msg' => 'Form Pendaftaran ini masih menjadi referensi di Pendaftaran Siswa.!'
            ], 422);
        }
        $registration_form->delete();
        return response()->json([
            'data'  => $registration_form,
            'msg'   => 'Form registrasi berhasil dihapus',
        ], 200);
    }
    
    public function fromSchool($sch_id)
    {
        $sch = RegistrationForm::where(['school_id' => $sch_id, 'status' => '1'])
                ->orderBy('id', 'desc')->first();
        return response()->json(['data' => $sch], 200);
    }
}
