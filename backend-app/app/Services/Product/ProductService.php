<?php

namespace App\Services\Product;

use App\Exceptions\BigcommerceIntegrationException;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\BigCommerce\IntegrationService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class ProductService
{
    public function sync(User $shop, IntegrationService $integrationService)
    {
        $sync = true;

        $latestProduct = Product::where('user_id', $shop->id)
            ->orderBy('id', 'desc')
            ->first();
        $query = [
            'limit' => 250,
            'id:min' => $latestProduct ? $latestProduct->bigcommerce_id : 0,
        ];

        while ($sync) {
            try {
                $products = $integrationService->getProducts($query);
            } catch (BigcommerceIntegrationException $e) {
                Log::alert('Failed to fetch products');
                return;
            } catch (ConnectionException $e) {
                Log::alert('Failed to connect to BigCommerce');
                return;
            }
            if (empty($products)) {
                $sync = false;
            }

            foreach ($products as $productItem) {
                $productData = [
                    'name' => $productItem['name'],
                    'sku' => $productItem['sku'],
                    'bigcommerce_id' => $productItem['id'],
                    'price' => $productItem['price'],
                    'user_id' => $shop->id,
                ];

                $product = Product::updateOrCreate($productData);

                $this->attachCategories($product, $productItem['categories']);

                $variants = $integrationService->getProductVariants($productItem['id']);

                $this->attachVariants($product, $variants, $productItem['price']);

                $query['id:min'] = $productItem['id'] + 1;
            }
        }
    }

    private function attachCategories(Product $product, array $categories)
    {
        $categoryIds = Category::whereIn('bigcommerce_id', $categories)->pluck('id')->toArray();

        $product->categories()->sync($categoryIds);
    }

    private function attachVariants(Product $product, array $variants, $price)
    {
        foreach ($variants as $variant) {
            $product->variants()->updateOrCreate([
                'bigcommerce_id' => $variant['id'],
            ], [
                'sku' => $variant['sku'],
                'price' => $variant['price'] ? $variant['price'] : $price,
                'option_values' => $variant['option_values'],
            ]);
        }
    }


}
