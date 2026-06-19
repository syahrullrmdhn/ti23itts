<?php

use App\Models\Student;
use App\Models\Semester;
use App\Models\Timeline;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$inputPath = $argv[1] ?? null;

if (!$inputPath || !is_file($inputPath)) {
    fwrite(STDERR, "Student import JSON path is required.\n");
    exit(1);
}

$payload = json_decode(file_get_contents($inputPath), true, flags: JSON_THROW_ON_ERROR);
$students = $payload['students'] ?? [];

DB::transaction(function () use ($payload, $students): void {
    $activeNims = collect($students)->pluck('nim')->all();

    Student::query()
        ->when($activeNims !== [], fn ($query) => $query->whereNotIn('nim', $activeNims))
        ->delete();

    foreach ($students as $student) {
        Student::query()->updateOrCreate(
            ['nim' => $student['nim']],
            $student
        );
    }

    Timeline::query()->updateOrCreate(
        ['id' => Timeline::query()->value('id') ?? 1],
        [
            'initial_count' => (int) ($payload['total_rows'] ?? count($students)),
            'current_count' => count($students),
        ]
    );

    Semester::query()
        ->where('semester', Semester::query()->max('semester'))
        ->update(['student_count' => count($students)]);
});

echo json_encode([
    'students' => Student::query()->count(),
    'active_students' => Student::query()->where('status', 'Aktif')->count(),
    'users' => \App\Models\User::query()->count(),
], JSON_PRETTY_PRINT).PHP_EOL;
