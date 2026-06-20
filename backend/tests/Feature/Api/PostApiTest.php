<?php

namespace Tests\Feature\Api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_read_published_posts_and_detail_by_slug(): void
    {
        Post::create([
            'title' => 'Cerita Pertama',
            'slug' => 'cerita-pertama',
            'excerpt' => 'Ringkas',
            'content' => 'Konten lengkap',
            'is_published' => true,
            'published_at' => now(),
        ]);

        Post::create([
            'title' => 'Draft',
            'slug' => 'draft',
            'excerpt' => 'Draft',
            'content' => 'Draft content',
            'is_published' => false,
        ]);

        $this->getJson('/api/posts')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.slug', 'cerita-pertama');

        $this->getJson('/api/posts/cerita-pertama')
            ->assertOk()
            ->assertJsonPath('title', 'Cerita Pertama');

        $this->getJson('/api/posts/draft')->assertNotFound();
    }

    public function test_admin_can_crud_posts(): void
    {
        Sanctum::actingAs(User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'passwordaman',
        ]));

        $postId = $this->postJson('/api/posts', [
            'title' => 'Cerita Kedua',
            'excerpt' => 'Ringkasan cerita kedua',
            'content' => 'Isi cerita kedua',
            'is_published' => true,
        ])->assertCreated()->json('id');

        $this->putJson("/api/posts/{$postId}", [
            'title' => 'Cerita Kedua Update',
            'excerpt' => 'Ringkasan cerita kedua update',
            'content' => 'Isi cerita kedua update',
            'is_published' => true,
        ])->assertOk()->assertJsonPath('title', 'Cerita Kedua Update');

        $this->getJson('/api/admin/posts')
            ->assertOk()
            ->assertJsonFragment(['id' => $postId, 'title' => 'Cerita Kedua Update']);

        $this->getJson("/api/admin/posts/{$postId}")
            ->assertOk()
            ->assertJsonPath('id', $postId)
            ->assertJsonPath('title', 'Cerita Kedua Update');

        $this->deleteJson("/api/posts/{$postId}")
            ->assertNoContent();
    }

    public function test_story_view_counter_counts_unique_visitors(): void
    {
        Post::create([
            'title' => 'Cerita Dilihat',
            'slug' => 'cerita-dilihat',
            'excerpt' => 'Ringkas',
            'content' => 'Konten lengkap',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->postJson('/api/posts/cerita-dilihat/view', [
            'visitor_id' => 'browser-yang-sama',
        ])->assertOk()->assertJsonPath('views_count', 1);

        $this->postJson('/api/posts/cerita-dilihat/view', [
            'visitor_id' => 'browser-yang-sama',
        ])->assertOk()->assertJsonPath('views_count', 1);

        $this->postJson('/api/posts/cerita-dilihat/view', [
            'visitor_id' => 'browser-yang-berbeda',
        ])->assertOk()->assertJsonPath('views_count', 2);

        $this->getJson('/api/posts/cerita-dilihat')
            ->assertOk()
            ->assertJsonPath('views_count', 2);
    }
}
