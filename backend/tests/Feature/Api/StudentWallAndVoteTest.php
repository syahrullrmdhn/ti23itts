<?php

namespace Tests\Feature\Api;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentWallAndVoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_leave_edit_and_delete_wall_message_once_per_profile(): void
    {
        $student = Student::create([
            'nim' => '1002230001',
            'name' => 'Target Student',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        Student::create([
            'nim' => '1002230002',
            'name' => 'Pengirim Pesan',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        Student::create([
            'nim' => '1002230003',
            'name' => 'Orang Lain',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        $messageId = $this->postJson("/api/students/{$student->id}/messages", [
            'nim' => '1002230002',
            'message' => 'Semangat terus ya, kamu keren.',
        ])->assertCreated()->json('wall_message.id');

        $this->postJson("/api/students/{$student->id}/messages", [
            'nim' => '1002230002',
            'message' => 'Pesan kedua',
        ])->assertStatus(422);

        $this->putJson("/api/students/{$student->id}/messages/{$messageId}", [
            'nim' => '1002230002',
            'message' => 'Semangat terus ya, kamu makin keren.',
        ])->assertOk()->assertJsonPath('wall_message.message', 'Semangat terus ya, kamu makin keren.');

        $this->deleteJson("/api/students/{$student->id}/messages/{$messageId}", [
            'nim' => '1002230003',
        ])->assertStatus(422);

        $this->deleteJson("/api/students/{$student->id}/messages/{$messageId}", [
            'nim' => '1002230002',
        ])->assertOk();
    }

    public function test_student_can_vote_badges_and_public_detail_returns_summary(): void
    {
        $student = Student::create([
            'nim' => '1002230001',
            'name' => 'Target Student',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        Student::create([
            'nim' => '1002230002',
            'name' => 'Voter A',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        Student::create([
            'nim' => '1002230003',
            'name' => 'Voter B',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        $this->postJson("/api/students/{$student->id}/vote", [
            'nim' => '1002230002',
            'badge' => 'paling_dicari',
        ])->assertOk();

        $this->postJson("/api/students/{$student->id}/vote", [
            'nim' => '1002230003',
            'badge' => 'paling_dicari',
        ])->assertOk();

        $this->postJson("/api/students/{$student->id}/vote", [
            'nim' => '1002230002',
            'badge' => 'paling_dicari',
        ])->assertStatus(422);

        $this->getJson("/api/students/{$student->id}/public")
            ->assertOk()
            ->assertJsonFragment([
                'key' => 'paling_dicari',
                'label' => 'Paling Dicari',
                'count' => 2,
            ])
            ->assertJsonFragment([
                'key' => 'paling_dicari',
                'label' => 'Paling Dicari',
            ]);
    }
}
