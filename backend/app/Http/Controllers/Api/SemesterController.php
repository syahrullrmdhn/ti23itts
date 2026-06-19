<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    protected function rules(bool $partial = false): array
    {
        $prefix = $partial ? 'sometimes|' : '';

        return [
            'semester' => $prefix . 'required|integer|min:1',
            'period' => $prefix . 'required|string|max:255',
            'icon' => $prefix . 'required|string|max:50',
            'student_count' => $prefix . 'required|integer|min:0',
            'lecturers' => $prefix . 'nullable|array',
            'lecturers.*.name' => 'required_with:lecturers|string|max:255',
            'lecturers.*.isAnomaly' => 'nullable|boolean',
        ];
    }

    protected function normalizePayload(array $validated): array
    {
        $validated['lecturers'] = collect($validated['lecturers'] ?? [])
            ->filter(fn ($lecturer) => filled($lecturer['name'] ?? null))
            ->map(fn ($lecturer) => [
                'name' => trim((string) $lecturer['name']),
                'isAnomaly' => (bool) ($lecturer['isAnomaly'] ?? false),
            ])
            ->values()
            ->all();

        return $validated;
    }

    public function index()
    {
        $semesters = Semester::orderBy('semester')->get();
        return response()->json($semesters);
    }

    public function store(Request $request)
    {
        $validated = $this->normalizePayload(
            $request->validate($this->rules())
        );

        $semester = Semester::create($validated);

        return response()->json($semester, 201);
    }

    public function show(string $id)
    {
        $semester = Semester::findOrFail($id);
        return response()->json($semester);
    }

    public function update(Request $request, string $id)
    {
        $semester = Semester::findOrFail($id);

        $validated = $this->normalizePayload(
            $request->validate($this->rules(partial: true))
        );

        $semester->update($validated);

        return response()->json($semester);
    }

    public function destroy(string $id)
    {
        $semester = Semester::findOrFail($id);
        $semester->delete();

        return response()->json(null, 204);
    }
}
