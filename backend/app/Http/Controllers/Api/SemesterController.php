<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    public function index()
    {
        $semesters = Semester::orderBy('semester')->get();
        return response()->json($semesters);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester' => 'required|integer',
            'period' => 'required|string',
            'icon' => 'required|string|max:10',
            'student_count' => 'required|integer',
            'lecturers' => 'required|array'
        ]);

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

        $validated = $request->validate([
            'semester' => 'sometimes|required|integer',
            'period' => 'sometimes|required|string',
            'icon' => 'sometimes|required|string|max:10',
            'student_count' => 'sometimes|required|integer',
            'lecturers' => 'sometimes|required|array'
        ]);

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
