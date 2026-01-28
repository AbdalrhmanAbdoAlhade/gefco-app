<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreInfoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ServiceController;

// Admin CRUD Controllers
use App\Http\Controllers\Admin\AdminAchievementController;
use App\Http\Controllers\Admin\AdminEquipmentController;
use App\Http\Controllers\Admin\AdminOurWorkController;
use App\Http\Controllers\Admin\AdminPartnerController;
use App\Http\Controllers\Admin\AdminClientController;

/*
|--------------------------------------------------------------------------
| Public Routes (المسارات العامة - للجميع)
|--------------------------------------------------------------------------
*/

// المصادقة
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// معلومات المتجر (عرض فقط)
Route::get('/store-info', [StoreInfoController::class, 'index']);

Route::get('services', [ServiceController::class, 'index']);
Route::get('services/{id}', [ServiceController::class, 'show']);
// عرض البيانات (عرض فقط لكل الجداول)
Route::get('/achievements', [App\Http\Controllers\AchievementController::class, 'index']);
Route::get('/achievements/{id}', [App\Http\Controllers\AchievementController::class, 'show']);

Route::get('/equipments', [App\Http\Controllers\EquipmentController::class, 'index']);
Route::get('/equipments/{id}', [App\Http\Controllers\EquipmentController::class, 'show']);

Route::get('/our-works', [App\Http\Controllers\OurWorkController::class, 'index']);
Route::get('/our-works/{id}', [App\Http\Controllers\OurWorkController::class, 'show']);
Route::post('/messages', [App\Http\Controllers\MessageController::class, 'store']);
Route::get('/partners', [App\Http\Controllers\PartnerController::class, 'index']);
Route::get('/partners/{id}', [App\Http\Controllers\PartnerController::class, 'show']);

Route::get('/clients', [App\Http\Controllers\ClientController::class, 'index']);
Route::get('/clients/{id}', [App\Http\Controllers\ClientController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Protected Routes (المسارات المحمية - تحتاج تسجيل دخول)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // --- مسارات المستخدم العادي (User) ---
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
Route::get('messages', [App\Http\Controllers\MessageController::class, 'index']);      // رؤية كل الرسائل
    Route::get('messages/{message}', [App\Http\Controllers\MessageController::class, 'show']); // رؤية رسالة محددة
    Route::post('messages/{message}', [App\Http\Controllers\MessageController::class, 'update']); // تحديث (إذا احتجت)
    Route::delete('messages/{message}', [App\Http\Controllers\MessageController::class, 'destroy']); // حذف الرسالة
    /*
    |--------------------------------------------------------------------------
    | Admin Routes (مسارات الأدمن فقط)
    |--------------------------------------------------------------------------
    */
Route::middleware(['role:admin'])->prefix('admin')->group(function () {

    // --- Achievements (الإنجازات) ---
    Route::get('achievements', [AdminAchievementController::class, 'index']);
    Route::post('achievements', [AdminAchievementController::class, 'store']);

    Route::post('achievements/{id}', [AdminAchievementController::class, 'update']); // التعديل POST
    Route::delete('achievements/{id}', [AdminAchievementController::class, 'destroy']);

    // --- Equipments (المعدات) ---
    Route::get('equipments', [AdminEquipmentController::class, 'index']);
    Route::post('equipments', [AdminEquipmentController::class, 'store']);
    
    Route::post('equipments/{id}', [AdminEquipmentController::class, 'update']); // التعديل POST
    Route::delete('equipments/{id}', [AdminEquipmentController::class, 'destroy']);

    // --- Our Works (أعمالنا) ---
    Route::get('our-works', [AdminOurWorkController::class, 'index']);
    Route::post('our-works', [AdminOurWorkController::class, 'store']);

    Route::post('our-works/{id}', [AdminOurWorkController::class, 'update']); // التعديل POST
    Route::delete('our-works/{id}', [AdminOurWorkController::class, 'destroy']);

    // --- Partners (الشركاء) ---
    Route::get('partners', [AdminPartnerController::class, 'index']);
    Route::post('partners', [AdminPartnerController::class, 'store']);

    Route::post('partners/{id}', [AdminPartnerController::class, 'update']); // التعديل POST
    Route::delete('partners/{id}', [AdminPartnerController::class, 'destroy']);

    // --- Clients (العملاء) ---
    Route::get('clients', [AdminClientController::class, 'index']);
    Route::post('clients', [AdminClientController::class, 'store']);
  
    Route::post('clients/{id}', [AdminClientController::class, 'update']); // التعديل POST
    Route::delete('clients/{id}', [AdminClientController::class, 'destroy']);
// إدارة الخدمات
    Route::post('services', [AdminController::class, 'storeService']);          // إنشاء
    Route::post('services/{id}', [AdminController::class, 'updateService']);    // تحديث (استخدم Post مع _method=PUT لرفع الصور)
    Route::delete('services/{id}', [AdminController::class, 'deleteService']);
    // إدارة معلومات المتجر
    Route::post('/store-info/update', [AdminController::class, 'updateStoreInfo']);
});

});
