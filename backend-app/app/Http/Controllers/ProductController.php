<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\utils\ResponseWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();

        $products->where('user_id', auth()->id());
        if ($request->query('category_id')) {
            $products->whereHas('categories', function ($query) use ($request) {
                $query->where('categories.id', $request->category_id);
            });
        }
        return ProductResource::collection($products->paginate(50));
    }

    public function show(int $id)
    {
        $product = Product::where('user_id', auth()->id())->findOrFail($id);

        return response()->json(['data' => new ProductResource($product)]);
    }
}
