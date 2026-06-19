<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EpisodeController extends Controller
{
    private function rules(bool $partial = false): array
    {
        $prefix = $partial ? 'sometimes|' : '';

        return [
            'category' => $prefix . 'required|string|max:255',
            'title' => $prefix . 'required|string|max:255',
            'media_type' => $prefix . 'required|in:image,video',
            'media_source' => $prefix . 'required|in:url,upload',
            'image_url' => 'nullable|string|max:2048',
            'video_url_input' => 'nullable|string|max:2048',
            'media_file' => 'nullable|file|max:51200',
            'short_description' => $prefix . 'required|string',
            'full_description' => $prefix . 'required|string',
            'date' => $prefix . 'required|string|max:255',
            'participants' => $prefix . 'required|string|max:255',
        ];
    }

    private function isStoredMedia(?string $path): bool
    {
        return filled($path) && !preg_match('/^https?:\/\//i', $path);
    }

    private function deleteStoredMedia(?string $path): void
    {
        if ($this->isStoredMedia($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function normalizePayload(Request $request, ?Episode $episode = null, bool $partial = false): array
    {
        $validated = $request->validate($this->rules($partial));

        $payload = [
            'category' => $validated['category'] ?? $episode?->category,
            'title' => $validated['title'] ?? $episode?->title,
            'short_description' => $validated['short_description'] ?? $episode?->short_description,
            'full_description' => $validated['full_description'] ?? $episode?->full_description,
            'date' => $validated['date'] ?? $episode?->date,
            'participants' => $validated['participants'] ?? $episode?->participants,
        ];

        $mediaType = $validated['media_type'] ?? $episode?->media_type ?? 'image';
        $mediaSource = $validated['media_source'] ?? $episode?->media_source ?? 'url';

        $payload['media_type'] = $mediaType;
        $payload['media_source'] = $mediaSource;

        if ($mediaSource === 'url') {
            if ($mediaType === 'image') {
                $imageUrl = trim((string) ($validated['image_url'] ?? ''));

                if ($imageUrl === '') {
                    throw ValidationException::withMessages([
                        'image_url' => 'Link gambar wajib diisi untuk media image.',
                    ]);
                }

                if ($episode && $episode->media_source === 'upload') {
                    $this->deleteStoredMedia($episode->image);
                    $this->deleteStoredMedia($episode->video_url);
                }

                $payload['image'] = $imageUrl;
                $payload['video_url'] = null;
            } else {
                $videoUrl = trim((string) ($validated['video_url_input'] ?? ''));

                if ($videoUrl === '') {
                    throw ValidationException::withMessages([
                        'video_url_input' => 'Link video wajib diisi untuk media video.',
                    ]);
                }

                if ($episode && $episode->media_source === 'upload') {
                    $this->deleteStoredMedia($episode->image);
                    $this->deleteStoredMedia($episode->video_url);
                }

                $payload['image'] = '';
                $payload['video_url'] = $videoUrl;
            }

            return $payload;
        }

        $file = $request->file('media_file');

        if (!$file) {
            throw ValidationException::withMessages([
                'media_file' => 'File wajib diunggah untuk media upload.',
            ]);
        }

        $mimeType = $file->getMimeType() ?? '';

        if ($mediaType === 'image' && !str_starts_with($mimeType, 'image/')) {
            throw ValidationException::withMessages([
                'media_file' => 'File harus berupa gambar JPG, PNG, atau WebP.',
            ]);
        }

        if ($mediaType === 'video' && !str_starts_with($mimeType, 'video/')) {
            throw ValidationException::withMessages([
                'media_file' => 'File harus berupa video MP4, MOV, atau WebM.',
            ]);
        }

        if ($episode && $episode->media_source === 'upload') {
            $this->deleteStoredMedia($episode->image);
            $this->deleteStoredMedia($episode->video_url);
        }

        $storedPath = $file->store('episodes', 'public');

        if ($mediaType === 'image') {
            $payload['image'] = $storedPath;
            $payload['video_url'] = null;
        } else {
            $payload['image'] = '';
            $payload['video_url'] = $storedPath;
        }

        return $payload;
    }

    public function index()
    {
        $episodes = Episode::orderBy('created_at', 'desc')->get();
        return response()->json($episodes);
    }

    public function store(Request $request)
    {
        $validated = $this->normalizePayload($request);

        $episode = Episode::create($validated);

        return response()->json($episode, 201);
    }

    public function show(string $id)
    {
        $episode = Episode::findOrFail($id);
        return response()->json($episode);
    }

    public function update(Request $request, string $id)
    {
        $episode = Episode::findOrFail($id);

        $validated = $this->normalizePayload($request, $episode, true);

        $episode->update($validated);

        return response()->json($episode);
    }

    public function destroy(string $id)
    {
        $episode = Episode::findOrFail($id);

        if ($episode->media_source === 'upload') {
            $this->deleteStoredMedia($episode->image);
            $this->deleteStoredMedia($episode->video_url);
        }

        $episode->delete();

        return response()->json(null, 204);
    }
}
