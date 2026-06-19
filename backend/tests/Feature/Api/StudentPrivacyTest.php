<?php

namespace Tests\Feature\Api;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StudentPrivacyTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_students_endpoint_hides_private_fields(): void
    {
        Student::create([
            'nim' => '1002230001',
            'name' => 'Mahasiswa Aktif',
            'email' => 'student@example.com',
            'gender' => 'Laki-laki',
            'birth_place' => 'Tangerang',
            'birth_date' => '2002-01-01',
            'religion' => 'Islam',
            'phone' => '081234567890',
            'class_type' => 'Pagi',
            'entry_type' => 'Reguler',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        Student::create([
            'nim' => '1002230002',
            'name' => 'Mahasiswa Nonaktif',
            'role' => "Mahasiswa TI '23",
            'status' => 'Cuti',
        ]);

        $this->getJson('/api/students')
            ->assertOk()
            ->assertJsonCount(1)
            ->assertJsonPath('0.name', 'Mahasiswa Aktif')
            ->assertJsonMissingPath('0.nim')
            ->assertJsonMissingPath('0.email')
            ->assertJsonMissingPath('0.birth_place')
            ->assertJsonMissingPath('0.birth_date')
            ->assertJsonMissingPath('0.phone')
            ->assertJsonMissingPath('0.religion');
    }

    public function test_only_authenticated_admin_can_read_private_student_data(): void
    {
        Student::create([
            'nim' => '1002230001',
            'name' => 'Mahasiswa Aktif',
            'email' => 'student@example.com',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ]);

        $this->getJson('/api/admin/students')->assertUnauthorized();

        Sanctum::actingAs(User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'passwordaman',
        ]));

        $this->getJson('/api/admin/students')
            ->assertOk()
            ->assertJsonPath('0.nim', '1002230001')
            ->assertJsonPath('0.email', 'student@example.com');
    }

    public function test_public_student_detail_shows_safe_profile_fields_only(): void
    {
        $student = Student::create([
            'nim' => '1002230001',
            'name' => 'Mahasiswa Aktif',
            'email' => 'student@example.com',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
            'fun_fact' => 'Suka begadang.',
            'message' => 'Tetap saling dukung ya.',
        ]);

        $this->getJson("/api/students/{$student->id}/public")
            ->assertOk()
            ->assertJsonPath('name', 'Mahasiswa Aktif')
            ->assertJsonPath('message', 'Tetap saling dukung ya.')
            ->assertJsonMissingPath('nim')
            ->assertJsonMissingPath('email');
    }

    public function test_unauthenticated_api_request_without_json_accept_header_returns_401(): void
    {
        $this->get('/api/admin/students')
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }
}
