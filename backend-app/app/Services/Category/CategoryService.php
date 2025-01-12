<?php

namespace App\Services\Category;

use App\Exceptions\BigcommerceIntegrationException;
use App\Models\Category;
use App\Models\User;
use App\Services\BigCommerce\IntegrationService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    public function sync(User $shop, IntegrationService $integrationService) {
        $sync = true;
        $latestCategory = Category::where('user_id', $shop->id)
            ->orderBy('id', 'desc')
            ->first();

        $query = [
            'limit' => 250,
            'id:min' => $latestCategory ? $latestCategory->bigcommerce_id : 0,
        ];

        while ($sync) {
            try {
                $categories = $integrationService->getCategories($query);
            } catch (BigcommerceIntegrationException $e) {
                Log::alert('Failed to fetch categories');
                $this->error('There was a problem fetching categories');
                return;
            } catch (ConnectionException $e) {
                Log::alert('Failed to connect to BigCommerce');
                $this->error('There was a problem connecting to BigCommerce');
                return;
            }

            if (empty($categories)) {
                $sync = false;
            }

            foreach ($categories as $category) {
                $categoryData = [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'parent_id' => $category['parent_id'],
                    'bigcommerce_id' => $category['id'],
                    'user_id' => $shop->id,
                ];

                Category::updateOrCreate($categoryData);

                $query['id:min'] = $category['id'] + 1;
            }

        }
    }
}
