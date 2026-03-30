<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProvinceRequest;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $limit     = $request->get('limit', null);
        $provinces = Province::with('cities');

        if ($request->q) {
            $provinces->where('name', 'like', "%{$request->q}%");
        }

        if ($limit) {
            return ProvinceResource::collection($provinces->paginate($limit));
        }

        return ProvinceResource::collection($provinces->get());
    }

    public function store(ProvinceRequest $request)
    {
        $validated = $request->validated();

        // Mapping prov_name -> name untuk kolom DB
        $validated['name'] = $validated['prov_name'];
        unset($validated['prov_name']);

        // Jika prov_code kosong, set null agar tidak error di kolom bigint
        if (empty($validated['prov_code'])) {
            $validated['prov_code'] = null;
        } else {
            $validated['prov_code'] = (int) $validated['prov_code'];
        }

        $prov = Province::create($validated);

        return response()->json([
            'status' => 'success',
            'data'   => $prov,
        ], 201);
    }

    public function show(string $id)
    {
        $prov = Province::with('cities')->findOrFail($id);
        return new ProvinceResource($prov);
    }

    public function update(ProvinceRequest $request, string $id)
    {
        $prov      = Province::findOrFail($id);
        $validated = $request->validated();

        $validated['name'] = $validated['prov_name'];
        unset($validated['prov_name']);

        if (empty($validated['prov_code'])) {
            $validated['prov_code'] = null;
        } else {
            $validated['prov_code'] = (int) $validated['prov_code'];
        }

        $prov->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data successfully updated',
            'data'    => $prov,
        ]);
    }

    public function destroy(string $id)
    {
        Province::findOrFail($id)->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data successfully deleted',
        ]);
    }
}