<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Http\Requests\BlogRequest;
use App\Http\Resources\BlogResource;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BlogRequest $request)
    {
        // filter & pagination
        $page  = $request->page ?? 1;
        $limit = $request->limit ?? 10;
        $ofs   = ($page - 1) * $limit;
        $order = $request->order ?? 'published_at';
        $ordtp = $request->orderType ?? 'desc';
        $q = $request->q ?? null;
        $category = $request->category ?? null;
        $param = '';

        $data = Blog::select('*');
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
        if (!$request->user()->school_id) {
            $data->where('published_at', '!=', null);
        }
        if ($request->has('schoolId')) {
            $data->where('school_id', $request->schoolId);
            $param .= '&schoolId='.$request->schoolId;
        }
        if ($category) {
            $data->where('category', $request->category);
            $param .= '&category='.$request->category;
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
            'data'  => BlogResource::collection($data->get()),
            'count' => $count,
            'limit' => $limit,
            'nextPageUrl' => $nextPageUrl ? $nextPageUrl.$param : null,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($data['title']);
        $data['publisher'] = $request->user()->id;
        $data['school_id'] = $request->user()->school_id;
        if ($data['published_at']) {
            $data['published_at'] = Carbon::now();
        } else {
            $data['published_at'] = null;
        }
        if (isset($data['id'])) {
            unset($data['id']);
        }
        return response()->json([
            'data' => new BlogResource(Blog::create($data)),
            'msg'  =>'Post baru berhasil dibuat' . ($data['published_at'] ? ' dan dipublikasikan.' : ' sebagai draf.'),
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $news, BlogRequest $request)
    {
        if ($request->has('edit')) {
            return response()->json(['data' => $news], 200);
        }
        return response()->json(['data' => new BlogResource($news->with('school')->first())], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $news)
    {
        $data = $request->all();
        foreach($data as $k => $v) {
            $news->$k = $v;
        }
        if ($data['title']) {
            $news->slug = Str::slug($news->title);
        }
        $news->publisher = $request->user()->id;
        $news->school_id = $request->user()->school_id;

        if ($request->has('published_at')) {
            if ($request->published_at) {
                $news->published_at = Carbon::now();
            } else {
                $news->published_at = null;
            }
        }
        $news->save();
        return response()->json([
            'data' => $news,
            'msg'  =>'Post berhasil diubah' . ($request->published_at ? ' dan dipublikasikan.' : ' sebagai draf.'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $news)
    {
        $news->delete();
        return response()->json([
            'data'  => $news,
            'msg'   => 'Postingan berhasil dihapus',
        ], 200);
    }
    
    public function bySchool($schID, BlogRequest $request) {
        $limit = $request->get('limit', 10);
        $data = Blog::where('school_id', $schID)->with('school');
        if ($request->q) {
            $data->where('title', 'like', "%$request->q%");
        }
        return BlogResource::collection($data->pagination($limit));
    }
}
