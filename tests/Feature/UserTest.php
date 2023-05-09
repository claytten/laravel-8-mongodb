<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

use App\Models\User;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testListUsers()
    {
        // Create a test user
        $user = User::create([
            'name' => 'User 1', 
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ]);

        // Authenticate the user using Sanctum
        $token = $user->createToken('Test Token')->plainTextToken;

        // Set the authorization header with the token
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ];

        // Make a GET request to the API endpoint
        $response = $this->withHeaders($headers)->get('/api/user');

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response contains the test users
        $response->assertJson($user->toArray());
    }
}