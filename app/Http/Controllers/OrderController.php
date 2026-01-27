<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * 1. إنشاء طلب جديد (User)
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string',
            'phone' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $totalAmount = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                // حساب السعر (استخدام سعر الخصم إذا وجد، وإلا السعر الأصلي)
                $price = $product->discount_price > 0 ? $product->discount_price : $product->price;
                $subtotal = $price * $item['quantity'];
                $totalAmount += $subtotal;

                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ];
            }

            // إنشاء الطلب الرئيسي
            $order = Order::create([
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'address' => $request->address,
                'phone' => $request->phone,
                'status' => 'pending'
            ]);

            // إضافة عناصر الطلب
            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }

            return response()->json([
                'status' => true,
                'message' => 'تم تسجيل الطلب بنجاح',
                'order_id' => $order->id,
                'total' => $totalAmount
            ], 201);
        });
    }

    /**
     * 2. عرض طلبات المستخدم الحالي (User)
     */
    public function myOrders()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json(['status' => true, 'data' => $orders]);
    }

    /**
     * 3. عرض كافة الطلبات (Admin)
     */
    public function allOrders()
    {
        $orders = Order::with(['user', 'items.product'])->latest()->get();
        return response()->json(['status' => true, 'data' => $orders]);
    }

    /**
     * 4. تحديث حالة الطلب (Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,processing,completed,cancelled']);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json(['status' => true, 'message' => 'تم تحديث حالة الطلب إلى ' . $request->status]);
    }
}