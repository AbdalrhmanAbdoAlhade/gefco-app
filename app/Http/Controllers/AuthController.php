<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'role' => 'user', // افتراضي يوزر
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'تم تسجيل الخروج']);
    }

    // عرض بيانات البروفايل للمستخدم الحالي
    public function profile(Request $request)
    {
        return response()->json($request->user());
    }

    // تحديث بيانات البروفايل
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'string|max:255',
            'phone' => 'string',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($user->image)
                Storage::disk('public')->delete($user->image);
            $data['image'] = $request->file('image')->store('profiles', 'public');
        }

        $user->update($data);
        return response()->json(['message' => 'تم تحديث البروفايل', 'user' => $user]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'خطأ في البيانات'], 401);
        }
        return response()->json([
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ]);
    }
}