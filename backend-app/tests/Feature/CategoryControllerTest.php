<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_categories_for_authenticated_user()
    {
        // Create a user and authenticate them
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create categories for the authenticated user
        $categories = Category::factory()->count(5)->create(['user_id' => $user->id]);

        // Send a GET request to the categories index endpoint
        $response = $this->getJson('/api/categories');

        // Assert that the response status is 200 OK
        $response->assertStatus(200);

        // Assert that the response contains categories data
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure(['data' => [['id', 'name', 'description', 'parentId']]]);
    }

    /** @test */
    public function it_does_not_return_categories_for_other_users()
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Authenticate user1
        $this->actingAs($user1);

        // Create a category for user2
        $category = Category::factory()->create(['user_id' => $user2->id]);

        // Send a GET request to the categories index endpoint
        $response = $this->getJson('/api/categories');

        // Assert that the response does not contain user2's category
        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');
    }
}
