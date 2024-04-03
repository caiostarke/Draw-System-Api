<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserGetTest extends TestCase {

    use RefreshDatabase;

    /**
     * @group userTest
     * Tests the api edit form
     */
    public function test_user_router_return_user_data() {
        $this->actingAs(User::factory()->create());

        $response = $this->get('/api/user');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'draws' => [
                    '*' => [
                        'id',
                        'user_id',
                        'image',
                        'name',
                        'body_content',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]
        ]);
    }
}