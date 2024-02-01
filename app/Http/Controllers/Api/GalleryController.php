<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Http\Requests\GalleryRequest;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GalleryRequest $req)
    {
        // filter & pagination
        $page  = $req->page ?? 1;
        $limit = $req->limit ?? 12;
        $ofs   = ($page - 1) * $limit;
        $order = $req->order ?? 'id';
        $ordtp = $req->orderType ?? 'asc';
        $q = $req->q ?? null;
        $param = '';

        $schools = Gallery::select('*')->with('imageable');
        if ($q) {
            $schools->where(function($query) use ($q) {
                $query->where('caption', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
            });
            $param .= '&q='.$q;
        }
        $count = $schools->count();
        $nextPageUrl = null;
        if ($count > $limit * $page) {
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

        return response()->json([
            'data'  => $schools->offset($ofs)->limit($limit)->orderBy($order, $ordtp)->get(),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl ? $nextPageUrl.$param : null,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GalleryRequest $request)
    {
        $data = [];
        if ($request->hasFile('images')) {
            // $files = $request->files('images');
            foreach ($request->file('images') as $file) {
                if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                    $fileName = "G-".time()."_".str_replace('+', '_', $file->getClientOriginalName());
                    $path = $file->move('storage/galleries', $fileName);
                    $data[] = [
                        'caption'     => $request->caption ?? '',
                        'description' => $request->description ?? '',
                        'path'        => $path,
                        'school_id'   => $request->school_id ?? $request->user()->school_id
                    ];
                }
            }
            if (count($data)) {
                Gallery::insert($data);
                return response()->json(['msg'  =>'Gambar berhasil ditambah.'], 200);
            } else {
                return response()->json(['msg'  =>'Tidak ada gambar ditemukan.!'], 422);
            }
        }
        return response()->json(['msg'  =>'Tidak ada gambar ditemukan.!'], 422);
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
