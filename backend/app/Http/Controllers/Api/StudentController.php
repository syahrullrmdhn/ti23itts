<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentWallMessage;
use App\Models\StudentVote;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    private function resolveStudentByNim(Request $request): Student
    {
        $validated = $request->validate([
            'nim' => 'required|string|exists:students,nim',
        ]);

        return Student::query()->where('nim', $validated['nim'])->firstOrFail();
    }

    private function serializeVoteSummary(Student $student): array
    {
        $counts = $student->votes()
            ->selectRaw('badge, COUNT(*) as total')
            ->groupBy('badge')
            ->pluck('total', 'badge');

        $summary = collect(Student::BADGE_DEFINITIONS)
            ->map(function (string $label, string $key) use ($counts) {
                return [
                    'key' => $key,
                    'label' => $label,
                    'count' => (int) ($counts[$key] ?? 0),
                ];
            })
            ->values();

        $highlighted = collect($summary)
            ->sortByDesc('count')
            ->filter(fn (array $badge) => $badge['count'] > 0)
            ->take(3)
            ->values();

        return [
            'summary' => $summary,
            'highlighted' => $highlighted,
        ];
    }

    public function index()
    {
        $students = Student::query()
            ->where('status', 'Aktif')
            ->with('votes')
            ->orderBy('name')
            ->get(['id', 'name', 'role', 'status', 'photo', 'aib_photo', 'fun_fact', 'message']);

        return response()->json(
            $students->map(function (Student $student) {
                $voteSummary = $this->serializeVoteSummary($student);

                return [
                    ...$student->toArray(),
                    'vote_summary' => $voteSummary['summary'],
                    'highlighted_badges' => $voteSummary['highlighted'],
                ];
            })
        );
    }

    public function publicShow(string $id)
    {
        $student = Student::query()
            ->where('status', 'Aktif')
            ->with(['wallMessages' => fn ($query) => $query->latest()])
            ->findOrFail($id, ['id', 'name', 'role', 'status', 'photo', 'aib_photo', 'fun_fact', 'message']);
        $voteSummary = $this->serializeVoteSummary($student);

        return response()->json([
            ...$student->toArray(),
            'wall_messages' => $student->wallMessages->map(fn (StudentWallMessage $message) => [
                'id' => $message->id,
                'author_name' => $message->author_name,
                'message' => $message->message,
                'created_at' => $message->created_at,
                'updated_at' => $message->updated_at,
            ]),
            'vote_summary' => $voteSummary['summary'],
            'highlighted_badges' => $voteSummary['highlighted'],
        ]);
    }

    public function adminIndex()
    {
        return response()->json(
            Student::query()->orderBy('name')->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nim' => 'required|string|max:20|unique:students,nim',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'gender' => 'nullable|string|max:20',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:30',
            'class_type' => 'nullable|string|max:30',
            'entry_type' => 'nullable|string|max:30',
            'role' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Cuti,Alumni',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'aib_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'fun_fact' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        if ($request->hasFile('aib_photo')) {
            $validated['aib_photo'] = $request->file('aib_photo')->store('students', 'public');
        }

        $student = Student::create($validated);
        AuditLogger::record(
            $request->user(),
            'student.created',
            'student',
            $student->id,
            $student->name,
            "Menambahkan mahasiswa {$student->name}.",
            ['nim' => $student->nim, 'status' => $student->status]
        );

        return response()->json($student, 201);
    }

    public function show(string $id)
    {
        $student = Student::findOrFail($id);
        return response()->json($student);
    }

    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'nim' => 'sometimes|required|string|max:20|unique:students,nim,' . $student->id,
            'name' => 'sometimes|required|string|max:255',
            'email' => 'nullable|email|max:255',
            'gender' => 'nullable|string|max:20',
            'birth_place' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'religion' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:30',
            'class_type' => 'nullable|string|max:30',
            'entry_type' => 'nullable|string|max:30',
            'role' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:Aktif,Cuti,Alumni',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'aib_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'fun_fact' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        if ($request->hasFile('aib_photo')) {
            if ($student->aib_photo) {
                Storage::disk('public')->delete($student->aib_photo);
            }
            $validated['aib_photo'] = $request->file('aib_photo')->store('students', 'public');
        }

        $student->update($validated);
        AuditLogger::record(
            $request->user(),
            'student.updated',
            'student',
            $student->id,
            $student->name,
            "Memperbarui mahasiswa {$student->name}.",
            ['nim' => $student->nim, 'status' => $student->status]
        );

        return response()->json($student);
    }

    public function destroy(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        if ($student->aib_photo) {
            Storage::disk('public')->delete($student->aib_photo);
        }

        AuditLogger::record(
            $request->user(),
            'student.deleted',
            'student',
            $student->id,
            $student->name,
            "Menghapus mahasiswa {$student->name}.",
            ['nim' => $student->nim]
        );

        $student->delete();

        return response()->json(null, 204);
    }

    public function storeWallMessage(Request $request, string $id)
    {
        $studentProfile = Student::query()->where('status', 'Aktif')->findOrFail($id);
        $author = $this->resolveStudentByNim($request);
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        if (StudentWallMessage::query()->where('student_id', $studentProfile->id)->where('author_nim', $author->nim)->exists()) {
            throw ValidationException::withMessages([
                'nim' => 'Kamu cuma bisa kirim satu pesan untuk tiap mahasiswa.',
            ]);
        }

        $message = StudentWallMessage::query()->create([
            'student_id' => $studentProfile->id,
            'author_nim' => $author->nim,
            'author_name' => $author->name,
            'message' => $validated['message'],
        ]);

        return response()->json([
            'message' => 'Pesan berhasil dikirim.',
            'wall_message' => $message,
        ], 201);
    }

    public function updateWallMessage(Request $request, string $studentId, string $messageId)
    {
        $studentProfile = Student::query()->where('status', 'Aktif')->findOrFail($studentId);
        $author = $this->resolveStudentByNim($request);
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = StudentWallMessage::query()
            ->where('student_id', $studentProfile->id)
            ->where('id', $messageId)
            ->firstOrFail();

        if ($message->author_nim !== $author->nim) {
            throw ValidationException::withMessages([
                'nim' => 'Kamu hanya bisa edit pesan milikmu sendiri.',
            ]);
        }

        $message->update([
            'author_name' => $author->name,
            'message' => $validated['message'],
        ]);

        return response()->json([
            'message' => 'Pesan berhasil diperbarui.',
            'wall_message' => $message->fresh(),
        ]);
    }

    public function destroyWallMessage(Request $request, string $studentId, string $messageId)
    {
        $studentProfile = Student::query()->where('status', 'Aktif')->findOrFail($studentId);
        $author = $this->resolveStudentByNim($request);

        $message = StudentWallMessage::query()
            ->where('student_id', $studentProfile->id)
            ->where('id', $messageId)
            ->firstOrFail();

        if ($message->author_nim !== $author->nim) {
            throw ValidationException::withMessages([
                'nim' => 'Kamu hanya bisa hapus pesan milikmu sendiri.',
            ]);
        }

        $message->delete();

        return response()->json([
            'message' => 'Pesan berhasil dihapus.',
        ]);
    }

    public function vote(Request $request, string $id)
    {
        $studentProfile = Student::query()->where('status', 'Aktif')->findOrFail($id);
        $voter = $this->resolveStudentByNim($request);
        $validated = $request->validate([
            'badge' => 'required|string|in:' . implode(',', array_keys(Student::BADGE_DEFINITIONS)),
        ]);

        if (StudentVote::query()
            ->where('student_id', $studentProfile->id)
            ->where('voter_nim', $voter->nim)
            ->where('badge', $validated['badge'])
            ->exists()) {
            throw ValidationException::withMessages([
                'nim' => 'Kamu sudah vote badge ini untuk mahasiswa tersebut.',
            ]);
        }

        StudentVote::query()->create([
            'student_id' => $studentProfile->id,
            'voter_nim' => $voter->nim,
            'voter_name' => $voter->name,
            'badge' => $validated['badge'],
        ]);

        $voteSummary = $this->serializeVoteSummary($studentProfile);

        return response()->json([
            'message' => 'Vote badge berhasil disimpan.',
            'vote_summary' => $voteSummary['summary'],
            'highlighted_badges' => $voteSummary['highlighted'],
        ]);
    }
}
