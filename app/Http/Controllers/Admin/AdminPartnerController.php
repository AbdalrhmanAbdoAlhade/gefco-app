<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPartnerController extends Controller
{
    public function index()
    {
        return response()->json(Partner::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'logo' => 'required|image'
        ]);

        $data['logo'] = $request->file('logo')->store('partners', 'public');

        return response()->json(Partner::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'logo' => 'sometimes|image'
        ]);

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($partner->logo);
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($data);

        return response()->json($partner);
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);

        Storage::disk('public')->delete($partner->logo);
        $partner->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
