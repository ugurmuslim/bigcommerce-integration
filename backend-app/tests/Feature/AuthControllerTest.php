<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful login.
     *
     * @return void
     */
    public function test_successful_login()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'email' => 'optimum7@example.com',
            'password' => bcrypt('password'),
        ]);

        // Make login request with valid credentials
        $response = $this->postJson('/api/login', [
            'email' => 'optimum7@example.com',
            'password' => 'password',
        ]);

        // Assert response status and structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                    ],
                ],
            ]);
    }

    /**
     * Test failed login due to invalid credentials.
     *
     * @return void
     */
    public function test_failed_login_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert response status and error message
        $response->assertStatus(401)
            ->assertJson(['error' => 'Unauthorized']);
    }

    /**
     * Test failed login due to missing credentials.
     *
     * @return void
     */
    public function test_failed_login_missing_credentials()
    {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }
}
