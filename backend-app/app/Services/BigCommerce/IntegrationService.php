<?php

namespace App\Services\BigCommerce;

use App\Exceptions\BigcommerceIntegrationException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class IntegrationService
{
    private string $accessToken;
    private string $storeHash;

    public function __construct(string $accessToken, string $storeHash)
    {
        $this->accessToken = $accessToken;
        $this->storeHash = $storeHash;
    }

    /**
     * @throws BigcommerceIntegrationException
     * @throws ConnectionException
     */
    public function getCategories(array $query): array
    {
        $queryString = http_build_query($query);
        $response = Http::withHeaders([
            'X-Auth-Token' => $this->accessToken,
            'Accept' => 'application/json',
        ])->get($this->generateUrl() . '/catalog/categories?' . $queryString);

        if (!$response->ok()) {
            throw new BigcommerceIntegrationException('Failed to fetch categories from big-commerce');
        }

        return $response->json()['data'];
    }

    /**
     * @throws BigcommerceIntegrationException
     * @throws ConnectionException
     */
    public function getProducts(array $query): array
    {
        $queryString = http_build_query($query);
        $response = Http::withHeaders([
            'X-Auth-Token' => $this->accessToken,
            'Accept' => 'application/json',
        ])->get($this->generateUrl() . '/catalog/products?' . $queryString);

        if (!$response->ok()) {
            throw new BigcommerceIntegrationException('Failed to fetch products from big-commerce');
        }

        return $response->json()['data'];
    }

    public function getProductVariants(int $productId): array
    {
        $response = Http::withHeaders([
            'X-Auth-Token' => $this->accessToken,
            'Accept' => 'application/json',
        ])->get($this->generateUrl() . '/catalog/products/' . $productId . '/variants');

        if (!$response->ok()) {
            throw new BigcommerceIntegrationException('Failed to fetch products from big-commerce');
        }

        return $response->json()['data'];
    }


    public function generateUrl(): string
    {
        return 'https://api.bigcommerce.com/stores/' . $this->storeHash . '/v3';
    }
}
