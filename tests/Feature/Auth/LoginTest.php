<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testLoginSuccess()
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $requestData = [
            'email' => $user->email,
            'password' => $password,
        ];

        $response = $this->postJson('/api/login', $requestData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);
    }

    public function testLoginValidationFailure()
    {
        $response = $this->postJson('/api/login', []);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation errors',
                'data' => [
                    'email' => ['Email is required'],
                    'password' => ['Password is required'],
                ]
            ]);
    }

    public function testLoginFailureUserNotFound()
    {
        $requestData = [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/login', $requestData);

        $response->assertStatus(404)->assertJson(['message' => 'User not found']);
    }

    public function testLoginFailureInvalidCredentials()
    {
        $email = $this->faker->unique()->safeEmail;

        User::factory()->create([
            'name' => $this->faker->name,
            'password' => Hash::make('password123'),
            'email' => $email
        ]);

        $requestData = [
            'email' => $email,
            'password' => 'wrong_password'
        ];

        $response = $this->postJson('/api/login', $requestData);

        $response->assertStatus(401)->assertJson(['message' => 'Invalid password']);
    }

}