<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(
            User::query()
                ->latest()
                ->get(['id', 'name', 'email', 'created_at', 'updated_at'])
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create($validated);
        AuditLogger::record(
            $request->user(),
            'user.created',
            'user',
            $user->id,
            $user->name,
            "Menambahkan user admin {$user->name}.",
            ['email' => $user->email]
        );

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);
        AuditLogger::record(
            $request->user(),
            'user.updated',
            'user',
            $user->id,
            $user->name,
            "Memperbarui user admin {$user->name}.",
            ['email' => $user->email]
        );

        return response()->json($user);
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->is($user)) {
            return response()->json([
                'message' => 'Akun yang sedang digunakan tidak dapat dihapus.',
            ], 422);
        }

        AuditLogger::record(
            $request->user(),
            'user.deleted',
            'user',
            $user->id,
            $user->name,
            "Menghapus user admin {$user->name}.",
            ['email' => $user->email]
        );

        $user->tokens()->delete();
        $user->delete();

        return response()->noContent();
    }
}
