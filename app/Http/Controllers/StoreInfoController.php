<?php

namespace App\Http\Controllers;

use App\Models\StoreInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoreInfoController extends Controller
{
    /**
     * جلب كافة معلومات المتجر
     * يتم استخدامها في واجهة التطبيق (Footer, Contact Us, About)
     */
    public function index()
    {
        // جلب أول سجل فقط لأن المتجر له بيانات واحدة
        $store = StoreInfo::first();

        if (!$store) {
            return response()->json([
                'status' => false,
                'message' => 'لم يتم إعداد معلومات المتجر بعد'
            ], 404);
        }

        // تنسيق البيانات لتشمل الرابط الكامل للوجو
        return response()->json([
            'status' => true,
            'data' => [
                'name' => $store->name,
                'logo' => $store->logo ? asset('storage/' . $store->logo) : null,
                'phone' => $store->phone,
                'email' => $store->email,
                'address' => $store->address,
                'social_links' => $store->social_links, // ترجع كـ Array بفضل الـ Casts في المودل
                'updated_at' => $store->updated_at,
            ]
        ]);
    }
}