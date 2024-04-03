<?php

namespace Tests\Feature\API;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Laravel\Passport\Passport;


class DrawControllerTest extends TestCase {

    use RefreshDatabase;

    public function test_create_draw() {
        $this->actingAs(User::factory()->create());

        $data = [
            'title' => 'test',
            'body_contet' => 'testing body content',
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