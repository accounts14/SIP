<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WebProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $accessToken = $user->token();
        
        $now = Carbon::now();
        $accessTokenExpiresAt = Carbon::parse($accessToken->expires_at);
        if ($accessTokenExpiresAt->gte($now)) {
            return response()->json(['message' => 'Token has expired'], 401);
        }
    
        try {
            // DB::enableQueryLog();
            $products = Product::
                with(['brand'])
                ->with(['productType'])
                ->with(['productCategory'])
                ->with(['productVariants.unit'])
                ->with(['productVariants.material'])
                ->with(['productVariants.discount'])
                ->with(['productVariants.productVariantSizes'])
                ->get();
            // dd(DB::getQueryLog());
            return $products;
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
 
    public function show($id)
    {
        return Product::find($id);
    }

    public function store(Request $request)
    {
        return Product::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $article = Product::findOrFail($id);
        $article->update($request->all());

        return $article;
    }

    public function delete(Request $request, $id)
    {
        $article = Product::findOrFail($id);
        $article->delete();

        return 204;
    }
}
