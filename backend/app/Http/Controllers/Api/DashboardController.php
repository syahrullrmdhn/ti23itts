<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Post;
use App\Models\Semester;
use App\Models\Student;
use App\Models\Timeline;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'stats' => [
                'students' => Student::count(),
                'episodes' => Episode::count(),
                'posts' => Post::count(),
                'semesters' => Semester::count(),
                'lecturers' => $this->countLecturers(),
            ],
            'recentActivities' => $this->buildRecentActivities(),
        ]);
    }

    private function countLecturers(): int
    {
        return Semester::query()
            ->get(['lecturers'])
            ->flatMap(function (Semester $semester) {
                return collect($semester->lecturers)
                    ->map(function (mixed $lecturer) {
                        if (is_array($lecturer)) {
                            return $lecturer['name'] ?? null;
                        }

                        return is_string($lecturer) ? $lecturer : null;
                    })
                    ->filter();
            })
            ->unique()
            ->count();
    }

    private function buildRecentActivities(): array
    {
        return collect()
            ->concat($this->studentActivities())
            ->concat($this->episodeActivities())
            ->concat($this->postActivities())
            ->concat($this->semesterActivities())
            ->concat($this->timelineActivities())
            ->sortByDesc('sort_at')
            ->take(3)
            ->values()
            ->map(function (array $activity, int $index) {
                unset($activity['sort_at']);
                $activity['id'] = $index + 1;

                return $activity;
            })
            ->all();
    }

    private function studentActivities(): Collection
    {
        return Student::query()
            ->latest('updated_at')
            ->take(3)
            ->get()
            ->map(function (Student $student) {
                $wasUpdated = $student->updated_at?->gt($student->created_at);

                return [
                    'icon' => '✨',
                    'title' => $wasUpdated ? 'Mahasiswa diperbarui' : 'Mahasiswa baru ditambah',
                    'description' => $wasUpdated
                        ? "{$student->name} sukses diperbarui di database."
                        : "{$student->name} sukses masuk radar database.",
                    'time' => $this->humanizeTime($student->updated_at),
                    'sort_at' => $student->updated_at,
                ];
            });
    }

    private function episodeActivities(): Collection
    {
        return Episode::query()
            ->latest('updated_at')
            ->take(3)
            ->get()
            ->map(function (Episode $episode) {
                $wasUpdated = $episode->updated_at?->gt($episode->created_at);

                return [
                    'icon' => '🐒',
                    'title' => $wasUpdated ? 'Episode diperbarui' : 'Episode baru rilis!',
                    'description' => $wasUpdated
                        ? "Episode \"{$episode->title}\" berhasil diperbarui."
                        : "Aib \"{$episode->title}\" berhasil di-publish.",
                    'time' => $this->humanizeTime($episode->updated_at),
                    'sort_at' => $episode->updated_at,
                ];
            });
    }

    private function semesterActivities(): Collection
    {
        return Semester::query()
            ->latest('updated_at')
            ->take(3)
            ->get()
            ->map(function (Semester $semester) {
                $wasUpdated = $semester->updated_at?->gt($semester->created_at);

                return [
                    'icon' => '🔥',
                    'title' => $wasUpdated ? 'Timeline di-update' : 'Semester baru ditambah',
                    'description' => $wasUpdated
                        ? "Data perjalanan semester {$semester->semester} udah disegarkan."
                        : "Semester {$semester->semester} berhasil ditambahkan ke timeline.",
                    'time' => $this->humanizeTime($semester->updated_at),
                    'sort_at' => $semester->updated_at,
                ];
            });
    }

    private function postActivities(): Collection
    {
        return Post::query()
            ->latest('updated_at')
            ->take(3)
            ->get()
            ->map(function (Post $post) {
                $wasUpdated = $post->updated_at?->gt($post->created_at);

                return [
                    'icon' => '📝',
                    'title' => $wasUpdated ? 'Cerita diperbarui' : 'Cerita baru diterbitkan',
                    'description' => $wasUpdated
                        ? "Cerita \"{$post->title}\" berhasil diperbarui."
                        : "Cerita \"{$post->title}\" berhasil ditambahkan.",
                    'time' => $this->humanizeTime($post->updated_at),
                    'sort_at' => $post->updated_at,
                ];
            });
    }

    private function timelineActivities(): Collection
    {
        return Timeline::query()
            ->latest('updated_at')
            ->take(1)
            ->get()
            ->map(function (Timeline $timeline) {
                $wasUpdated = $timeline->updated_at?->gt($timeline->created_at);

                return [
                    'icon' => '📈',
                    'title' => $wasUpdated ? 'Statistik angkatan diperbarui' : 'Statistik angkatan dibuat',
                    'description' => "Jumlah mahasiswa aktif saat ini {$timeline->current_count} dari {$timeline->initial_count} data awal.",
                    'time' => $this->humanizeTime($timeline->updated_at),
                    'sort_at' => $timeline->updated_at,
                ];
            });
    }

    private function humanizeTime(?CarbonInterface $time): string
    {
        if (!$time) {
            return 'baru saja';
        }

        return $time->locale('id')->diffForHumans();
    }
}
