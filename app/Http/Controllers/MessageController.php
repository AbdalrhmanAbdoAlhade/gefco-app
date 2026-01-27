<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    // 1. عرض كل الرسائل (Index)
    public function index()
    {
        $messages = Message::latest()->get();
        return response()->json($messages);
    }

    // 2. عرض رسالة واحدة محددة (Show)
    public function show(Message $message)
    {
        return response()->json($message);
    }

    // 5. حذف رسالة (Destroy)
    public function destroy(Message $message)
    {
        $message->delete();
        return response()->json(['message' => 'تم حذف الرسالة بنجاح']);
    }

    // 3. إضافة رسالة جديدة (Store) - موجودة مسبقاً
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'phone'   => 'required|string|max:20',
            'message' => 'required|string',
        ]);

        $newMessage = Message::create($validated);
        return response()->json(['status' => 'success', 'data' => $newMessage], 201);
    }

    // 4. تحديث رسالة (Update)
    public function update(Request $request, Message $message)
    {
        $validated = $request->validate([
            'name'    => 'sometimes|required|string|max:255',
            'email'   => 'sometimes|required|email|max:255',
            'phone'   => 'sometimes|required|string|max:20',
            'message' => 'sometimes|required|string',
        ]);

        $message->update($validated);
        return response()->json(['message' => 'تم تحديث البيانات بنجاح', 'data' => $message]);
    }

}