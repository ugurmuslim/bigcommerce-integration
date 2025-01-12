<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Variants;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    public function test_product_index_returns_correct_structure()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Product::factory()
            ->count(3)
            ->for($user)
            ->has(Category::factory()->count(2))
            ->has(Variants::factory()->count(2))
            ->create();

        $response = $this->getJson('/api/products');

        // Assert the response status
        $response->assertStatus(200);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'categories' => [
                            '*' => [
                                'id',
                                'name',
                                'bigCommeceId',
                            ],
                        ],
                        'variants' => [
                            '*' => [
                                'id',
                                'name',
                                'price',
                                'sku',
                                'optionValues' => [
                                    'color',
                                    'size',
                                ],
                            ],
                        ],
                    ],
                ],
            ]);
    }
}
