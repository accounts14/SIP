<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Http\Requests\GalleryRequest;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GalleryRequest $request)
    {
        $data = [
            'caption'     => $request->caption ?? '',
            'description' => $request->description ?? '',
        ];
        $file = $request->file('file');
        if ($file->getClientOriginalName()) {
            $fileName = "G-".time()."_".$file->getClientOriginalName();
            $file->move('galleries', $fileName);
            $data['path'] = 'galleries/'.$fileName;
            // unlink('galleries/'.$setting->value);
        } else {
            return response()->json(['msg'  =>'Tidak ada file untuk disimpan.!'], 422);
        }
        return response()->json([
            'data' => Gallery::create($data),
            'msg'  =>'Gambar berhasil ditambah.',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        return response()->json(['data' => $gallery], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GalleryRequest $request, Gallery $gallery)
    {
        $file = $request->file('file');
        if ($file->getClientOriginalName()) {
            $fileName = "G-".time()."_".$file->getClientOriginalName();
            $file->move('galleries', $fileName);
            unlink($gallery->path);
            $gallery->path = 'galleries/'.$fileName;
        } else {
            return response()->json(['msg'  =>'Tidak ada file untuk disimpan.!'], 422);
        }
        if (isset($request->caption)) {
            $gallery->caption = $request->caption;
        }
        if (isset($request->description)) {
            $gallery->description = $request->description;
        }
        $gallery->save();
        return response()->json([
            'data' => $gallery,
            'msg'  => 'Gambar berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        unlink($gallery->path);
        $gallery->delete();
        return response()->json(['msg'  => 'Gambar berhasil dihapus'], 200);
    }
}
