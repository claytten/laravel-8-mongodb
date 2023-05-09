<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
     * Test registration
     */
    public function testRegistration()
    {
        $response = $this->postJson('/api/signup', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'confirm_password' => 'password',
            'address' => '123 Main St.',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }

    /**
     * Test login
     */
    public function testLogin()
    {
        $user = User::create([
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertNotNull($response['access_token']);

        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/me');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data'    => $user->toArray(),
            'message' => 'User created successfully.',
        ]);
    }

    /**
     * Test logout
     */
    public function testLogout()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(204);
    }

    /**
     * Test authenticated user
     */
    public function testAuthenticatedUser()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'address' => '123 Main St.',
        ]);
        Auth::login($user);

        $response = $this->get('/api/me');

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'data'    => $user->toArray(),
            'message' => 'User created successfully.',
        ]);
    }
}
