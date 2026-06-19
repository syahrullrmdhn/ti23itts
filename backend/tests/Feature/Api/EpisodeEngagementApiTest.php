<?php

namespace Tests\Feature\Api;

use App\Models\Episode;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EpisodeEngagementApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_like_and_comment_once_per_episode_using_nim(): void
    {
        $episode = Episode::create([
            'category' => 'DRAMA',
            'title' => 'Open Letter',
            'image' => 'https://example.com/image.jpg',
            'media_type' => 'image',
            'media_source' => 'url',
            'video_url' => null,
            'short_description' => 'Short',
            'full_description' => 'Full',
            'date' => 'Juni 2026',
            'participants' => 'TI23',
        ]);

        Student::create([
            'nim' => '1002230001',
            'name' => 'Mahasiswa Aktif',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        $this->postJson("/api/episodes/{$episode->id}/like", [
            'nim' => '1002230001',
        ])->assertOk()->assertJsonPath('likes_count', 1);

        $this->postJson("/api/episodes/{$episode->id}/comment", [
            'nim' => '1002230001',
            'comment' => 'Semangat terus buat teman-teman.',
        ])->assertCreated()->assertJsonPath('comments_count', 1);

        $this->postJson("/api/episodes/{$episode->id}/like", [
            'nim' => '1002230001',
        ])->assertStatus(422);

        $this->postJson("/api/episodes/{$episode->id}/comment", [
            'nim' => '1002230001',
            'comment' => 'Komen kedua',
        ])->assertStatus(422);
    }

    public function test_episode_index_marks_top_banyak_dicari(): void
    {
        $topEpisode = Episode::create([
            'category' => 'DRAMA',
            'title' => 'Top Episode',
            'image' => 'https://example.com/image-top.jpg',
            'media_type' => 'image',
            'media_source' => 'url',
            'video_url' => null,
            'short_description' => 'Short',
            'full_description' => 'Full',
            'date' => 'Juni 2026',
            'participants' => 'TI23',
        ]);

        $otherEpisode = Episode::create([
            'category' => 'DRAMA',
            'title' => 'Other Episode',
            'image' => 'https://example.com/image-other.jpg',
            'media_type' => 'image',
            'media_source' => 'url',
            'video_url' => null,
            'short_description' => 'Short',
            'full_description' => 'Full',
            'date' => 'Juni 2026',
            'participants' => 'TI23',
        ]);

        Student::create([
            'nim' => '1002230001',
            'name' => 'A',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        Student::create([
            'nim' => '1002230002',
            'name' => 'B',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        $this->postJson("/api/episodes/{$topEpisode->id}/like", ['nim' => '1002230001']);
        $this->postJson("/api/episodes/{$topEpisode->id}/comment", ['nim' => '1002230001', 'comment' => 'Mantap']);
        $this->postJson("/api/episodes/{$topEpisode->id}/like", ['nim' => '1002230002']);

        $this->postJson("/api/episodes/{$otherEpisode->id}/like", ['nim' => '1002230002']);

        $this->getJson('/api/episodes')
            ->assertOk()
            ->assertJsonFragment([
                'id' => $topEpisode->id,
                'is_top_banyak_dicari' => true,
            ]);
    }

    public function test_student_can_edit_and_delete_own_comment_using_saved_nim_identity(): void
    {
        $episode = Episode::create([
            'category' => 'DRAMA',
            'title' => 'Komentar Episode',
            'image' => 'https://example.com/image.jpg',
            'media_type' => 'image',
            'media_source' => 'url',
            'video_url' => null,
            'short_description' => 'Short',
            'full_description' => 'Full',
            'date' => 'Juni 2026',
            'participants' => 'TI23',
        ]);

        Student::create([
            'nim' => '1002230001',
            'name' => 'Mahasiswa Aktif',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        Student::create([
            'nim' => '1002230002',
            'name' => 'Teman Lain',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        $commentId = $this->postJson("/api/episodes/{$episode->id}/comment", [
            'nim' => '1002230001',
            'comment' => 'Komentar awal',
        ])->assertCreated()->json('comment.id');

        $this->putJson("/api/episodes/{$episode->id}/comment/{$commentId}", [
            'nim' => '1002230001',
            'comment' => 'Komentar sudah diedit',
        ])->assertOk()
            ->assertJsonPath('comment.comment', 'Komentar sudah diedit');

        $this->putJson("/api/episodes/{$episode->id}/comment/{$commentId}", [
            'nim' => '1002230002',
            'comment' => 'Mau ambil komentar orang',
        ])->assertStatus(422);

        $this->deleteJson("/api/episodes/{$episode->id}/comment/{$commentId}", [
            'nim' => '1002230002',
        ])->assertStatus(422);

        $this->deleteJson("/api/episodes/{$episode->id}/comment/{$commentId}", [
            'nim' => '1002230001',
        ])->assertOk()
            ->assertJsonPath('comments_count', 0);
    }
}
