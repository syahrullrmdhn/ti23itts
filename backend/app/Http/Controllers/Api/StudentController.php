<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::orderBy('name')->get();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'status' => 'required|in:Aktif,Cuti,Alumni',
            'photo' => 'nullable|image|max:2048',
            'aib_photo' => 'nullable|image|max:2048',
            'fun_fact' => 'nullable|string'
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('students', 'public');
        }

        if ($request->hasFile('aib_photo')) {
            $validated['aib_photo'] = $request->file('aib_photo')->store('students', 'public');
        }

        $student = Student::create($validated);

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
            'name' => 'sometimes|required|string|max:255',
            'role' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:Aktif,Cuti,Alumni',
            'photo' => 'nullable|image|max:2048',
            'aib_photo' => 'nullable|image|max:2048',
            'fun_fact' => 'nullable|string'
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

        return response()->json($student);
    }

    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);

        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        if ($student->aib_photo) {
            Storage::disk('public')->delete($student->aib_photo);
        }

        $student->delete();

        return response()->json(null, 204);
    }
}
