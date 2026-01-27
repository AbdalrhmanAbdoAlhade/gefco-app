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
            'name'          => 'required|string',
            'image'         => 'required|array', // تأكد أنها مصفوفة
            'image.*'       => 'image|mimes:jpeg,png,jpg,gif|max:2048', // التحقق من كل ملف داخل المصفوفة
            'cover_image'   => 'required|image',
            'description'   => 'nullable|string'
        ]);

        // تخزين مصفوفة الصور
        $images = [];
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $images[] = $file->store('works', 'public');
            }
        }

        $data['image'] = $images;
        $data['cover_image'] = $request->file('cover_image')->store('works', 'public');

        return response()->json(OurWork::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $work = OurWork::findOrFail($id);

        $data = $request->validate([
            'name'          => 'sometimes|string',
            'image'         => 'sometimes|array',
            'image.*'       => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image'   => 'sometimes|image',
            'description'   => 'nullable|string'
        ]);

        // تحديث مصفوفة الصور
        if ($request->hasFile('image')) {
            // حذف الصور القديمة من السيرفر
            if ($work->image) {
                Storage::disk('public')->delete($work->image);
            }

            $images = [];
            foreach ($request->file('image') as $file) {
                $images[] = $file->store('works', 'public');
            }
            $data['image'] = $images;
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

        // حذف كل الصور الموجودة في المصفوفة من السيرفر
        if ($work->image) {
            Storage::disk('public')->delete($work->image);
        }
        
        // حذف صورة الغلاف
        Storage::disk('public')->delete($work->cover_image);

        $work->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
