<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEquipmentController extends Controller
{
    public function index()
    {
        return response()->json(Equipment::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string',
            'image'       => 'required|image',
            'description' => 'nullable|string'
        ]);

        $data['image'] = $request->file('image')->store('equipments', 'public');

        return response()->json(Equipment::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $equipment = Equipment::findOrFail($id);

        $data = $request->validate([
            'name'        => 'sometimes|string',
            'image'       => 'sometimes|image',
            'description' => 'nullable|string'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($equipment->image);
            $data['image'] = $request->file('image')->store('equipments', 'public');
        }

        $equipment->update($data);

        return response()->json($equipment);
    }

    public function destroy($id)
    {
        $equipment = Equipment::findOrFail($id);

        Storage::disk('public')->delete($equipment->image);
        $equipment->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
