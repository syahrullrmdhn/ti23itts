<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\EpisodeComment;
use App\Models\EpisodeLike;
use App\Models\Student;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
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
        $episodes = Episode::query()
            ->withCount(['likes', 'comments'])
            ->with([
                'comments' => fn ($query) => $query->latest(),
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $topEpisodeId = $this->resolveTopEpisodeId($episodes);

        return response()->json(
            $episodes->map(function (Episode $episode) use ($topEpisodeId) {
                return [
                    ...$episode->toArray(),
                    'is_top_banyak_dicari' => $episode->id === $topEpisodeId,
                ];
            })
        );
    }

    public function store(Request $request)
    {
        $validated = $this->normalizePayload($request);

        $episode = Episode::create($validated);
        AuditLogger::record(
            $request->user(),
            'episode.created',
            'episode',
            $episode->id,
            $episode->title,
            "Menambahkan episode {$episode->title}.",
            ['category' => $episode->category]
        );

        return response()->json($episode, 201);
    }

    public function show(string $id)
    {
        $episode = Episode::findOrFail($id);
        return response()->json($episode);
    }

    public function publicShow(string $id)
    {
        $episode = Episode::query()
            ->withCount(['likes', 'comments'])
            ->with([
                'comments' => fn ($query) => $query->latest(),
            ])
            ->findOrFail($id);

        $topEpisodeId = $this->resolveTopEpisodeId(
            Episode::query()
                ->withCount(['likes', 'comments'])
                ->get()
        );

        return response()->json([
            ...$episode->toArray(),
            'is_top_banyak_dicari' => $episode->id === $topEpisodeId,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $episode = Episode::findOrFail($id);

        $validated = $this->normalizePayload($request, $episode, true);

        $episode->update($validated);
        AuditLogger::record(
            $request->user(),
            'episode.updated',
            'episode',
            $episode->id,
            $episode->title,
            "Memperbarui episode {$episode->title}.",
            ['category' => $episode->category]
        );

        return response()->json($episode);
    }

    public function destroy(string $id)
    {
        $episode = Episode::findOrFail($id);

        if ($episode->media_source === 'upload') {
            $this->deleteStoredMedia($episode->image);
            $this->deleteStoredMedia($episode->video_url);
        }

        AuditLogger::record(
            $request->user(),
            'episode.deleted',
            'episode',
            $episode->id,
            $episode->title,
            "Menghapus episode {$episode->title}.",
            ['category' => $episode->category]
        );

        $episode->delete();

        return response()->json(null, 204);
    }

    public function like(Request $request, string $id)
    {
        $episode = Episode::findOrFail($id);
        $student = $this->resolveStudentByNim($request);

        if (EpisodeLike::query()->where('episode_id', $episode->id)->where('student_nim', $student->nim)->exists()) {
            throw ValidationException::withMessages([
                'nim' => 'Like hanya boleh satu kali per episode.',
            ]);
        }

        EpisodeLike::query()->create([
            'episode_id' => $episode->id,
            'student_nim' => $student->nim,
            'student_name' => $student->name,
        ]);

        return response()->json([
            'message' => 'Like berhasil disimpan.',
            'likes_count' => $episode->likes()->count(),
        ]);
    }

    public function comment(Request $request, string $id)
    {
        $episode = Episode::findOrFail($id);
        $student = $this->resolveStudentByNim($request);

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        if (EpisodeComment::query()->where('episode_id', $episode->id)->where('student_nim', $student->nim)->exists()) {
            throw ValidationException::withMessages([
                'nim' => 'Komentar hanya boleh satu kali per episode.',
            ]);
        }

        $comment = EpisodeComment::create([
            'episode_id' => $episode->id,
            'student_nim' => $student->nim,
            'student_name' => $student->name,
            'comment' => $validated['comment'],
        ]);

        return response()->json([
            'message' => 'Komentar berhasil dikirim.',
            'comment' => $comment,
            'comments_count' => $episode->comments()->count(),
        ], 201);
    }

    public function updateComment(Request $request, string $episodeId, string $commentId)
    {
        $episode = Episode::findOrFail($episodeId);
        $student = $this->resolveStudentByNim($request);
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = EpisodeComment::query()
            ->where('episode_id', $episode->id)
            ->where('id', $commentId)
            ->firstOrFail();

        if ($comment->student_nim !== $student->nim) {
            throw ValidationException::withMessages([
                'nim' => 'Kamu hanya bisa mengedit komentar milikmu sendiri.',
            ]);
        }

        $comment->update([
            'comment' => $validated['comment'],
            'student_name' => $student->name,
        ]);

        return response()->json([
            'message' => 'Komentar berhasil diperbarui.',
            'comment' => $comment->fresh(),
            'comments_count' => $episode->comments()->count(),
        ]);
    }

    public function destroyComment(Request $request, string $episodeId, string $commentId)
    {
        $episode = Episode::findOrFail($episodeId);
        $student = $this->resolveStudentByNim($request);

        $comment = EpisodeComment::query()
            ->where('episode_id', $episode->id)
            ->where('id', $commentId)
            ->firstOrFail();

        if ($comment->student_nim !== $student->nim) {
            throw ValidationException::withMessages([
                'nim' => 'Kamu hanya bisa menghapus komentar milikmu sendiri.',
            ]);
        }

        $comment->delete();

        return response()->json([
            'message' => 'Komentar berhasil dihapus.',
            'comments_count' => $episode->comments()->count(),
        ]);
    }

    private function resolveStudentByNim(Request $request): Student
    {
        $validated = $request->validate([
            'nim' => 'required|string|exists:students,nim',
        ]);

        return Student::query()->where('nim', $validated['nim'])->firstOrFail();
    }

    private function resolveTopEpisodeId(Collection $episodes): ?int
    {
        return $episodes
            ->sortByDesc(function (Episode $episode) {
                $score = ($episode->likes_count ?? 0) + ($episode->comments_count ?? 0);

                return sprintf('%010d-%010d-%010d', $score, $episode->comments_count ?? 0, $episode->likes_count ?? 0);
            })
            ->first()?->id;
    }
}
