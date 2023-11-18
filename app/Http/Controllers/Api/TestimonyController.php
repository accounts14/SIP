<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimony;
use App\Models\Schools;
use Illuminate\Support\Facades\Validator;

class TestimonyController extends Controller
{
    public function index()
    {
        $testimonies = Testimony::all();
        return response()->json(['data' => $testimonies]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
            'school_id' => 'required|exists:schools,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $testimony = new Testimony([
            'user_id' => auth()->user()->id,
            'user_name' => auth()->user()->name,
            'school_id' => $request->input('school_id'),
            'content' => $request->input('content'),
        ]);

        $testimony->save();

        return response()->json([
            'message' => 'Success'
        ]);

        // if($testimony) {
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Berhasil'  
        //     ], 201);
        // }

        // //return JSON process insert failed 
        // return response()->json([
        //     'success' => false,
        //     'message' => 'Gagagl'
        // ], 409);
    }
}
