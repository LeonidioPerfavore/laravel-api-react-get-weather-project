<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function testRegistrationSuccess()
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password'
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)->assertJsonStructure(['token']);
    }

    public function testRegistrationWithoutDataFailure()
    {
        $response = $this->postJson('/api/register', []);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation errors',
                'data' => [
                    'name' => ['Name is required'],
                    'email' => ['Email is required'],
                    'password' => ['Password is required'],
                ]
            ]);
    }

    public function testRegistrationValidationFailure()
    {
        $response = $this->postJson('/api/register', [
            'name' => 1,
            'email' => 'fake_email!',
            'password' => 'short'
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation errors',
                'data' => [
                    'name' => ['Name must be a string'],
                    'email' => ['Email must be a valid email address'],
                    'password' => ['Password must be at least 8 characters'],
                ]
            ]);
    }

    public function testRegisterAttemptsWithRegisteredEmail()
    {
        $password = 'password123';
        $email = $this->faker->unique()->safeEmail;
        User::factory()->create([
            'name' => $this->faker->name,
            'password' => Hash::make($password),
            'email' => $email
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'Name',
            'email' => $email,
            'password' => $password
        ]);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation errors',
                'data' => [
                    'email' => ['This email is already taken'],
                ]
            ]);
    }
}
