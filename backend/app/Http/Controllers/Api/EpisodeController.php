<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index()
    {
        $episodes = Episode::orderBy('created_at', 'desc')->get();
        return response()->json($episodes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'image' => 'required|string',
            'short_description' => 'required|string',
            'full_description' => 'required|string',
            'date' => 'required|string',
            'participants' => 'required|string'
        ]);

        $episode = Episode::create($validated);

        return response()->json($episode, 201);
    }

    public function show(string $id)
    {
        $episode = Episode::findOrFail($id);
        return response()->json($episode);
    }

    public function update(Request $request, string $id)
    {
        $episode = Episode::findOrFail($id);

        $validated = $request->validate([
            'category' => 'sometimes|required|string|max:255',
            'title' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|required|string',
            'short_description' => 'sometimes|required|string',
            'full_description' => 'sometimes|required|string',
            'date' => 'sometimes|required|string',
            'participants' => 'sometimes|required|string'
        ]);

        $episode->update($validated);

        return response()->json($episode);
    }

    public function destroy(string $id)
    {
        $episode = Episode::findOrFail($id);
        $episode->delete();

        return response()->json(null, 204);
    }
}
