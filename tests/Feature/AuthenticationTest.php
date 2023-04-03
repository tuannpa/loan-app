<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function testRequiredFieldsForRegistration()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The name field is required. (and 3 more errors)',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                    'role' => ['The role field is required.'],
                ]
            ]);
    }

    public function testPasswordConfirmation()
    {
        $userData = [
            'name' => 'Tuan Nguyen',
            'email' => 'npatuan.uit@gmail.com',
            'password' => 'password',
            'role' => 'admin'
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The password field confirmation does not match.',
                'errors' => [
                    'password' => ['The password field confirmation does not match.']
                ]
            ]);
    }

    public function testUniqueEmail()
    {
        $userData = [
            'name' => 'Tuan Nguyen',
            'email' => 'npatuan.uit@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin'
        ];

        $attempts = 2;

        // First create a user. Then create another new user with the exact same data including the same email address.
        for ($i = 0; $i < $attempts; $i++) {
            $response = $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json']);

            if ($i === 1) {
                $response->assertStatus(500)
                    ->assertJson([
                        'message' => 'The given data was invalid.',
                        'errors' => [
                            'email' => ['The email has already been taken.']
                        ]
                    ]);
            }
        }
    }

    public function testSuccessfulRegistration()
    {
        $userData = [
            'name' => 'Tuan Nguyen',
            'email' => 'npatuan.uit@gmail.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin'
        ];

        $res = $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json']);

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                ],
                'access_token'
            ]);
    }

    public function testMustEnterEmailAndPassword()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The email field is required. (and 1 more error)',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    public function testSuccessfulLogin()
    {
        // Create a user.
        $user = User::factory()->create([
            'email' => 'npatuan.uit@gmail.com',
            'password' => bcrypt('password1'),
            'role' => 'admin'
        ]);

        // Use the created user for authentication.
        $loginData = ['email' => 'npatuan.uit@gmail.com', 'password' => 'password1'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'email_verified_at',
                    'created_at',
                    'updated_at',
                ],
                'access_token'
            ]);

        // Assert that the user is authenticated.
        $this->assertAuthenticated();
    }
}
