<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_username(): void
    {
        User::create([
            'name' => 'syahrul',
            'email' => 'syahrul@ti23itts.my.id',
            'password' => 'syahrulitts2026',
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'syahrul',
            'password' => 'syahrulitts2026',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
            ]);
    }

    public function test_user_can_login_with_email(): void
    {
        User::create([
            'name' => 'syahrul',
            'email' => 'syahrul@ti23itts.my.id',
            'password' => 'syahrulitts2026',
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'syahrul@ti23itts.my.id',
            'password' => 'syahrulitts2026',
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
            ]);
    }
}
