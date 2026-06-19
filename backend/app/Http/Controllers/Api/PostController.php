<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    private function generateUniqueSlug(string $title, ?Post $post = null, ?string $requestedSlug = null): string
    {
        $baseSlug = Str::slug(trim($requestedSlug ?: $title));
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'cerita';
        $slug = $baseSlug;
        $suffix = 2;

        while (
            Post::query()
                ->when($post, fn ($query) => $query->where('id', '!=', $post->id))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function rules(?Post $post = null): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('posts', 'slug')->ignore($post?->id),
            ],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    private function normalizePayload(Request $request, ?Post $post = null): array
    {
        $validated = $request->validate($this->rules($post));

        $title = trim((string) $validated['title']);
        $slug = $this->generateUniqueSlug($title, $post, (string) ($validated['slug'] ?? ''));

        $payload = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => trim((string) $validated['excerpt']),
            'content' => trim((string) $validated['content']),
            'is_published' => (bool) ($validated['is_published'] ?? true),
            'published_at' => $validated['published_at'] ?? ($post?->published_at ?? now()),
        ];

        if ($request->hasFile('cover_image')) {
            if ($post?->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }

            $payload['cover_image'] = $request->file('cover_image')->store('posts', 'public');
        }

        return $payload;
    }

    public function index()
    {
        return response()->json(
            Post::query()
                ->where('is_published', true)
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->get()
        );
    }

    public function adminIndex()
    {
        return response()->json(
            Post::query()
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->get()
        );
    }

    public function showBySlug(string $slug)
    {
        $post = Post::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return response()->json($post);
    }

    public function store(Request $request)
    {
        $post = Post::create($this->normalizePayload($request));

        AuditLogger::record(
            $request->user(),
            'post.created',
            'post',
            $post->id,
            $post->title,
            "Menambahkan cerita {$post->title}.",
            ['slug' => $post->slug, 'published' => $post->is_published]
        );

        return response()->json($post, 201);
    }

    public function show(Post $post)
    {
        return response()->json($post);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->normalizePayload($request, $post));

        AuditLogger::record(
            $request->user(),
            'post.updated',
            'post',
            $post->id,
            $post->title,
            "Memperbarui cerita {$post->title}.",
            ['slug' => $post->slug, 'published' => $post->is_published]
        );

        return response()->json($post);
    }

    public function destroy(Request $request, Post $post)
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        AuditLogger::record(
            $request->user(),
            'post.deleted',
            'post',
            $post->id,
            $post->title,
            "Menghapus cerita {$post->title}.",
            ['slug' => $post->slug]
        );

        $post->delete();

        return response()->json(null, 204);
    }
}
