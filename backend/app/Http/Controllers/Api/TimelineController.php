<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Timeline;
use App\Models\Semester;
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
        } else {
            $timeline = Timeline::create($validated);
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

        return response()->json($timeline);
    }
}
