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
        $students = Student::query()
            ->where('status', 'Aktif')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'role',
                'status',
                'photo',
                'aib_photo',
                'fun_fact',
            ]);

        return response()->json($students);
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
