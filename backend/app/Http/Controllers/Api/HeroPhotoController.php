<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroPhotoController extends Controller
{
    public function index()
    {
        return response()->json(
            HeroPhoto::query()
                ->orderBy('sort_order')
                ->orderByDesc('id')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $photo = HeroPhoto::create([
            'path' => $validated['photo']->store('hero-photos', 'public'),
            'sort_order' => (int) HeroPhoto::max('sort_order') + 1,
        ]);

        return response()->json($photo, 201);
    }

    public function destroy(string $id)
    {
        $photo = HeroPhoto::findOrFail($id);

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return response()->json(null, 204);
    }
}
