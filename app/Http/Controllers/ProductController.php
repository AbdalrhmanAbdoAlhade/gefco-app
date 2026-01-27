<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * عرض كل المنتجات (Pagination)
     */
    public function index()
    {
        // جلب المنتجات مع صورها والقسم التابع لها
        $products = Product::with(['images', 'category'])->latest()->paginate(12);

        // تعديل الروابط لتكون كاملة
        $products->getCollection()->transform(function ($product) {
            $product->images->map(function ($img) {
                $img->image_path = asset('storage/' . $img->image_path);
                return $img;
            });
            return $product;
        });

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }

    /**
     * عرض تفاصيل منتج واحد
     */
    public function show($id)
    {
        $product = Product::with(['images', 'category'])->find($id);

        if (!$product) {
            return response()->json(['status' => false, 'message' => 'المنتج غير موجود'], 404);
        }

        // تحويل روابط الصور
        $product->images->map(function ($img) {
            $img->image_path = asset('storage/' . $img->image_path);
            return $img;
        });

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }

    /**
     * البحث عن المنتجات بالاسم (عربي أو إنجليزي)
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        $products = Product::with('images')
            ->where('name_ar', 'LIKE', "%{$query}%")
            ->orWhere('name_en', 'LIKE', "%{$query}%")
            ->get();

        $products->transform(function ($product) {
            $product->images->map(function ($img) {
                $img->image_path = asset('storage/' . $img->image_path);
                return $img;
            });
            return $product;
        });

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }

    /**
     * جلب المنتجات التي عليها خصومات فقط
     */
    public function offers()
    {
        $products = Product::with('images')
            ->where('discount_price', '>', 0)
            ->latest()
            ->get();

        $products->transform(function ($product) {
            $product->images->map(function ($img) {
                $img->image_path = asset('storage/' . $img->image_path);
                return $img;
            });
            return $product;
        });

        return response()->json([
            'status' => true,
            'data' => $products
        ]);
    }
}