<?php

namespace Tests\Feature\Api;

use App\Models\Semester;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SemesterApiTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate(): void
    {
        Sanctum::actingAs(User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]));
    }

    public function test_admin_can_update_semester_with_empty_lecturer_list(): void
    {
        $this->authenticate();

        $semester = Semester::create([
            'semester' => 1,
            'period' => 'Sep 2023 - Jan 2024',
            'icon' => '🎯',
            'student_count' => 45,
            'lecturers' => [
                ['name' => 'Pak Budi Santoso', 'isAnomaly' => false],
            ],
        ]);

        $this->putJson("/api/semesters/{$semester->id}", [
            'semester' => 1,
            'period' => 'Sep 2023 - Jan 2024',
            'icon' => '🧑‍🏫 Semester 1',
            'student_count' => 45,
            'lecturers' => [],
        ])
            ->assertOk()
            ->assertJsonPath('icon', '🧑‍🏫 Semester 1')
            ->assertJsonPath('lecturers', []);
    }
}
