<?php

namespace Tests\Feature\Api;

use App\Models\HeroPhoto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class HeroPhotoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_endpoint_returns_uploaded_hero_photos(): void
    {
        HeroPhoto::create([
            'path' => 'hero-photos/sample-one.webp',
            'sort_order' => 2,
        ]);

        HeroPhoto::create([
            'path' => 'hero-photos/sample-two.webp',
            'sort_order' => 1,
        ]);

        $this->getJson('/api/hero-photos')
            ->assertOk()
            ->assertJsonPath('0.path', 'hero-photos/sample-two.webp')
            ->assertJsonPath('1.path', 'hero-photos/sample-one.webp');
    }

    public function test_admin_can_upload_and_delete_hero_photo(): void
    {
        Storage::fake('public');

        Sanctum::actingAs(User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]));

        $upload = UploadedFile::fake()->image('hero-photo.jpg', 1600, 900);

        $created = $this->post('/api/hero-photos', [
            'photo' => $upload,
        ], [
            'Accept' => 'application/json',
        ]);

        $created
            ->assertCreated()
            ->assertJsonPath('sort_order', 1);

        $path = $created->json('path');

        Storage::disk('public')->assertExists($path);

        $this->deleteJson('/api/hero-photos/' . $created->json('id'))
            ->assertNoContent();

        Storage::disk('public')->assertMissing($path);
        $this->assertDatabaseCount('hero_photos', 0);
    }
}
