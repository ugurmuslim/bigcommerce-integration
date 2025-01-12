<?php

namespace App\Console\Commands;

use App\Exceptions\BigcommerceIntegrationException;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\BigCommerce\IntegrationService;
use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class ProductSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncing Products with BigCommerce';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $shop = User::first();
        $sync = true;
        $integrationService = new IntegrationService($shop->access_token, $shop->store_hash);

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
                $this->error('There was a problem fetching products');
                return;
            } catch (ConnectionException $e) {
                Log::alert('Failed to connect to BigCommerce');
                $this->error('There was a problem connecting to BigCommerce');
                return;
            }
            $this->info('Fetched ' . count($products) . ' products');
            if(empty($products)) {
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

                $categoryIds = Category::whereIn('bigcommerce_id', $productItem['categories'])->pluck('id')->toArray();

                $product->categories()->sync($categoryIds);

                $variants =$integrationService->getProductVariants($productItem['id']);

                foreach ($variants as $variant) {
                    $product->variants()->updateOrCreate([
                        'bigcommerce_id' => $variant['id'],
                    ], [
                        'sku' => $variant['sku'],
                        'price' => $variant['price'] ? $variant['price'] :  $productItem['price'],
                        'option_values' => $variant['option_values'],
                    ]);
                }
                $query['id:min'] = $productItem['id'] + 1;
            }
        }
    }
}
