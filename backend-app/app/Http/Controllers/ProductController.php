<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\BigCommerce\IntegrationService;
use Illuminate\Http\Request;

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
        try {
            $product = Product::where('user_id', auth()->id())->findOrFail($id);
        } catch (   \Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }


        return response()->json(['data' => new ProductResource($product)]);
    }
}
