<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class DrawControllerTest extends TestCase {

    use RefreshDatabase;

    public function test_create_draw() {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $data = [
            'title' => 'test',
            'body_content' => 'testing body content',
            'image' => UploadedFile::fake()->image('avatar.jpg') 
        ];

        $response = $this->post('/api/draw', $data);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message'
        ]);

        $response->assertJsonFragment([
            'message' => 'Draw created successfully'
        ]);
    }
}   