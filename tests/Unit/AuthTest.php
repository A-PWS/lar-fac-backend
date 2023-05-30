<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;

class AuthTest extends TestCase
{
    // use RefreshDatabase;

     
    public $createdUserId = null;
    public function testUserRegistrationWithValidData()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'code' => 1,
                'message' => 'User created successfully',
            ])
            ->assertJsonStructure([
                'data' => [
                    'name',
                    'email',
                    'created_at',
                    'updated_at',
                    'id'
                ],
            ]);

            $responseData = $response->json(); // Convert the response to an array
            $this->createdUserId = $responseData['data']['id']; // Assign the id to $createdUserId
       
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);
    }


 
    /**
     * @depends testUserRegistrationWithValidData
     */
    public function testDeletingTheCreatedUser()
    {
        $response = $this->deleteJson('/api/deleteTestUser/john@example.com');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'success' => true,
                'code' => 1,
                'message' => 'User Deleted',
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $this->createdUserId,
        ]);
    }

    public function testDependency()
    {
        $this->assertTrue(true);
    }
}
