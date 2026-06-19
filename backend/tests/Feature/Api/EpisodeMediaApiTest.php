<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EpisodeMediaApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_store_image_episode_via_url(): void
    {
        Sanctum::actingAs(User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]));

        $response = $this->postJson('/api/episodes', [
            'category' => 'ZOOM FAILS',
            'title' => 'Monkey Business',
            'media_type' => 'image',
            'media_source' => 'url',
            'image_url' => 'https://example.com/episode.jpg',
            'short_description' => 'Deskripsi singkat',
            'full_description' => 'Deskripsi lengkap',
            'date' => 'Juni 2026',
            'participants' => 'Willy',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('media_type', 'image')
            ->assertJsonPath('image', 'https://example.com/episode.jpg');
    }

    public function test_admin_can_store_video_episode_via_upload(): void
    {
        Storage::fake('public');

        Sanctum::actingAs(User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]));

        $upload = UploadedFile::fake()->create('episode.mp4', 1024, 'video/mp4');

        $response = $this->post('/api/episodes', [
            'category' => 'LEGENDARY',
            'title' => 'Zoom Chaos',
            'media_type' => 'video',
            'media_source' => 'upload',
            'media_file' => $upload,
            'short_description' => 'Video chaos zoom',
            'full_description' => 'Video lengkap chaos zoom.',
            'date' => 'Juli 2026',
            'participants' => 'TI23',
        ], [
            'Accept' => 'application/json',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('media_type', 'video');

        $path = $response->json('video_url');

        $this->assertSame('', $response->json('image'));
        Storage::disk('public')->assertExists($path);

        $this->getJson('/api/episodes')
            ->assertOk()
            ->assertJsonPath('0.video_url', $path);
    }
}
