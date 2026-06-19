<?php

namespace Tests\Feature\Api;

use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuditLogApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_actions_are_recorded_in_audit_log(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'passwordaman',
        ]);

        Sanctum::actingAs($admin);

        $studentId = $this->postJson('/api/students', [
            'nim' => '1002230001',
            'name' => 'Mahasiswa Baru',
            'role' => "Mahasiswa TI '23",
            'status' => 'Aktif',
        ])->assertCreated()->json('id');

        $this->getJson('/api/audit-logs')
            ->assertOk()
            ->assertJsonFragment([
                'action' => 'student.created',
                'target_type' => 'student',
                'target_label' => 'Mahasiswa Baru',
            ]);

        $student = Student::findOrFail($studentId);
        $this->deleteJson("/api/students/{$student->id}")
            ->assertNoContent();

        $this->getJson('/api/audit-logs')
            ->assertOk()
            ->assertJsonFragment([
                'action' => 'student.deleted',
                'target_type' => 'student',
                'target_label' => 'Mahasiswa Baru',
            ]);
    }
}
