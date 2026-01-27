<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAchievementController extends Controller
{
    public function index()
    {
        return response()->json(Achievement::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string',
            'image' => 'required|image'
        ]);

        $data['image'] = $request->file('image')->store('achievements', 'public');

        return response()->json(Achievement::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);

        $data = $request->validate([
            'name'  => 'sometimes|string',
            'image' => 'sometimes|image'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($achievement->image);
            $data['image'] = $request->file('image')->store('achievements', 'public');
        }

        $achievement->update($data);

        return response()->json($achievement);
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);

        Storage::disk('public')->delete($achievement->image);
        $achievement->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
