<?php

namespace App\Console\Commands;

use App\Exceptions\BigcommerceIntegrationException;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\BigCommerce\IntegrationService;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
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
        $this->info('Product Sync started');
        $shops = User::all();

        foreach ($shops as $shop) {
            $integrationService = new IntegrationService($shop->access_token, $shop->store_hash);
            $productService = new ProductService();
            $productService->sync($shop, $integrationService);
        }


    }
}
