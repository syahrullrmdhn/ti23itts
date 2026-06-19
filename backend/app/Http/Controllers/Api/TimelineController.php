<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use App\Models\Semester;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $timeline = Timeline::first();
        $semesters = Semester::orderBy('semester')->get();

        return response()->json([
            'initialCount' => $timeline->initial_count ?? 0,
            'currentCount' => $timeline->current_count ?? 0,
            'semesters' => $semesters
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'initial_count' => 'required|integer',
            'current_count' => 'required|integer'
        ]);

        $timeline = Timeline::first();

        if ($timeline) {
            $timeline->update($validated);
            AuditLogger::record(
                $request->user(),
                'timeline.updated',
                'timeline',
                $timeline->id,
                'Timeline utama',
                'Memperbarui ringkasan timeline angkatan.',
                $validated
            );
        } else {
            $timeline = Timeline::create($validated);
            AuditLogger::record(
                $request->user(),
                'timeline.created',
                'timeline',
                $timeline->id,
                'Timeline utama',
                'Membuat ringkasan timeline angkatan.',
                $validated
            );
        }

        return response()->json($timeline, 201);
    }

    public function update(Request $request, string $id)
    {
        $timeline = Timeline::findOrFail($id);

        $validated = $request->validate([
            'initial_count' => 'sometimes|required|integer',
            'current_count' => 'sometimes|required|integer'
        ]);

        $timeline->update($validated);
        AuditLogger::record(
            $request->user(),
            'timeline.updated',
            'timeline',
            $timeline->id,
            'Timeline utama',
            'Memperbarui data timeline angkatan.',
            $validated
        );

        return response()->json($timeline);
    }
}
