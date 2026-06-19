<?php

namespace Tests\Feature\Api;

use App\Models\Episode;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Timeline;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_endpoint_returns_live_summary(): void
    {
        Sanctum::actingAs(User::create([
            'name' => 'syahrul',
            'email' => 'syahrul@ti23itts.my.id',
            'password' => 'syahrulitts2026',
        ]));

        Student::create([
            'name' => 'Ahmad Rizky',
            'role' => 'Koordinator',
            'status' => 'Aktif',
        ]);

        Student::create([
            'name' => 'Nadia Putri',
            'role' => 'Editor',
            'status' => 'Aktif',
        ]);

        Episode::create([
            'category' => 'ZOOM FAILS',
            'title' => 'Monkey Business',
            'image' => 'https://example.com/episode.jpg',
            'short_description' => 'Deskripsi singkat',
            'full_description' => 'Deskripsi lengkap',
            'date' => 'Juni 2026',
            'participants' => 'Ahmad Rizky, Nadia Putri',
        ]);

        Semester::create([
            'semester' => 1,
            'period' => 'Sep 2023 - Jan 2024',
            'icon' => '🎯',
            'student_count' => 45,
            'lecturers' => [
                ['name' => 'Pak Budi Santoso', 'isAnomaly' => false],
                ['name' => 'Bu Siti Rahma', 'isAnomaly' => false],
            ],
        ]);

        Semester::create([
            'semester' => 2,
            'period' => 'Feb 2024 - Jun 2024',
            'icon' => '🚀',
            'student_count' => 43,
            'lecturers' => [
                ['name' => 'Pak Budi Santoso', 'isAnomaly' => false],
                ['name' => 'Pak Ahmad Wijaya', 'isAnomaly' => false],
            ],
        ]);

        Timeline::create([
            'initial_count' => 45,
            'current_count' => 43,
        ]);

        $response = $this->getJson('/api/dashboard');

        $response
            ->assertOk()
            ->assertJsonPath('stats.students', 2)
            ->assertJsonPath('stats.episodes', 1)
            ->assertJsonPath('stats.semesters', 2)
            ->assertJsonPath('stats.lecturers', 3)
            ->assertJsonCount(3, 'recentActivities');
    }
}
