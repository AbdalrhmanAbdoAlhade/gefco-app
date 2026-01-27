<?php

namespace App\Http\Controllers;


use App\Models\StoreInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==========================================
    // 1. معلومات المتجر (Store Info)
    // ==========================================

    public function updateStoreInfo(Request $request)
    {
        // نأخذ أول سجل، إذا لم يوجد ننشئ كائن جديد
        $info = StoreInfo::first() ?? new StoreInfo();

        $data = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'social_links' => 'nullable|array'
        ]);

        if ($request->hasFile('logo')) {
            if ($info->logo)
                Storage::disk('public')->delete($info->logo);
            $data['logo'] = $request->file('logo')->store('store', 'public');
        }

        $info->fill($data);
        $info->save();

        return response()->json(['message' => 'تم تحديث بيانات المتجر بنجاح', 'data' => $info]);
    }



}