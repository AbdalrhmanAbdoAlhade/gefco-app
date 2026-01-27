<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OurWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminOurWorkController extends Controller
{
    public function index()
    {
        return response()->json(OurWork::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string',
            'image'       => 'required|image',
            'cover_image' => 'required|image',
            'description' => 'nullable|string'
        ]);

        $data['image'] = $request->file('image')->store('works', 'public');
        $data['cover_image'] = $request->file('cover_image')->store('works', 'public');

        return response()->json(OurWork::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $work = OurWork::findOrFail($id);

        $data = $request->validate([
            'name'        => 'sometimes|string',
            'image'       => 'sometimes|image',
            'cover_image' => 'sometimes|image',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($work->image);
            $data['image'] = $request->file('image')->store('works', 'public');
        }

        if ($request->hasFile('cover_image')) {
            Storage::disk('public')->delete($work->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('works', 'public');
        }

        $work->update($data);

        return response()->json($work);
    }

    public function destroy($id)
    {
        $work = OurWork::findOrFail($id);

        Storage::disk('public')->delete([$work->image, $work->cover_image]);
        $work->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
