<?php

namespace App\Http\Controllers;

use App\Models\StoreInfo;
use App\Models\Service; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // ==========================================
    // 1. معلومات المتجر (Store Info)
    // ==========================================
    public function updateStoreInfo(Request $request)
    {
        $info = StoreInfo::first() ?? new StoreInfo();

        $data = $request->validate([
            'name' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'experience' => 'nullable|string',
            'satisfied_clients' => 'nullable|string',
            'completed_projects' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'social_links' => 'nullable|array'
        ]);

        if ($request->hasFile('logo')) {
            if ($info->logo) Storage::disk('public')->delete($info->logo);
            $data['logo'] = $request->file('logo')->store('store', 'public');
        }

        $info->fill($data);
        $info->save();

        return response()->json(['message' => 'تم تحديث بيانات المتجر بنجاح', 'data' => $info]);
    }

    // ==========================================
    // 2. إدارة الخدمات (Services - CRUD)
    // ==========================================

    // إضافة خدمة جديدة
    public function storeService(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service = Service::create($data);

        return response()->json(['message' => 'تم إضافة الخدمة بنجاح', 'data' => $service], 201);
    }

    // تحديث خدمة موجودة
    public function updateService(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($service->image) Storage::disk('public')->delete($service->image);
            $data['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($data);

        return response()->json(['message' => 'تم تحديث الخدمة بنجاح', 'data' => $service]);
    }

    // حذف خدمة
    public function deleteService($id)
    {
        $service = Service::findOrFail($id);
        
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return response()->json(['message' => 'تم حذف الخدمة بنجاح']);
    }
}
