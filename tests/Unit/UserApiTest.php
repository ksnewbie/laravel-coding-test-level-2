<?php

namespace Tests\Unit;

use Laravel\Sanctum\Sanctum;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        Sanctum::actingAs(
            User::factory()->create(['role' => 'admin']),
            ['manage-users']
        );

        $data = [
            'name' => 'Test',
            'email' => 'test123@test.com',
            'username' => 'user1',
            'password' => 'testuser',
            'role' => 'product_owner'
        ];
        $response = $this->post(route('users.store'), $data);
        $response->assertOk();
        $response->assertJson(
            [
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'username' => $data['username'],
                    'role' => $data['role'],
                ]
            ]
        );
        $this->assertDatabaseHas(
            'users',
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'role' => $data['role'],
            ]
        );
    }
}
