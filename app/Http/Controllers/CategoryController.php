<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * عرض جميع الأقسام
     */
    public function index()
    {
        $categories = Category::all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name_ar' => $category->name_ar,
                'name_en' => $category->name_en,
                // إرجاع الرابط الكامل للصورة ليتم عرضها مباشرة في الفرونت إند
                'image' => $category->image ? asset('storage/' . $category->image) : null,
                'created_at' => $category->created_at,
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $categories
        ]);
    }

    /**
     * عرض قسم واحد مع المنتجات التابعة له
     */
    public function show($id)
    {
        // جلب القسم مع الصور الخاصة بكل منتج تابع له
        $category = Category::with(['products.images'])->find($id);

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'القسم غير موجود'
            ], 404);
        }

        // تنسيق البيانات لتشمل روابط الصور الكاملة
        $category->image = $category->image ? asset('storage/' . $category->image) : null;

        $category->products->map(function ($product) {
            $product->images->map(function ($img) {
                $img->image_path = asset('storage/' . $img->image_path);
                return $img;
            });
            return $product;
        });

        return response()->json([
            'status' => true,
            'data' => $category
        ]);
    }
}