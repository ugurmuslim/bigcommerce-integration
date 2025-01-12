<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\BigCommerce\IntegrationService;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;

class SyncController extends Controller
{
    public function __construct(private readonly ProductService $productService, private readonly CategoryService $categoryService)
    {
    }

    /**
     * Sync all products and categories from BigCommerce
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(): JsonResponse
    {
        /** @var User $shop */
        $shop = auth()->user();

        $integrationService = new IntegrationService($shop->access_token, $shop->store_hash);

        $this->categoryService->sync($shop, $integrationService);
        $this->productService->sync($shop, $integrationService);

        return response()->json(['message' => 'Sync completed']);

    }
}
