<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_manage_users(): void
    {
        $admin = User::create([
            'name' => 'syahrul',
            'email' => 'syahrul@ti23itts.my.id',
            'password' => 'syahrulitts2026',
        ]);

        Sanctum::actingAs($admin);

        $createResponse = $this->postJson('/api/users', [
            'name' => 'Admin Kedua',
            'email' => 'admin2@ti23itts.my.id',
            'password' => 'passwordbaru',
            'password_confirmation' => 'passwordbaru',
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('name', 'Admin Kedua')
            ->assertJsonMissingPath('password');

        $userId = $createResponse->json('id');

        $this->getJson('/api/users')
            ->assertOk()
            ->assertJsonCount(2);

        $this->putJson("/api/users/{$userId}", [
            'name' => 'Admin Konten',
            'email' => 'konten@ti23itts.my.id',
        ])
            ->assertOk()
            ->assertJsonPath('name', 'Admin Konten');

        $this->deleteJson("/api/users/{$userId}")
            ->assertNoContent();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    public function test_user_cannot_delete_their_own_account(): void
    {
        $admin = User::create([
            'name' => 'syahrul',
            'email' => 'syahrul@ti23itts.my.id',
            'password' => 'syahrulitts2026',
        ]);

        Sanctum::actingAs($admin);

        $this->deleteJson("/api/users/{$admin->id}")
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Akun yang sedang digunakan tidak dapat dihapus.');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}
