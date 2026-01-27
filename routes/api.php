<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreInfoController;
use App\Http\Controllers\AdminController;

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

// عرض البيانات (عرض فقط لكل الجداول)
Route::get('/achievements', [App\Http\Controllers\AchievementController::class, 'index']);
Route::get('/achievements/{id}', [App\Http\Controllers\AchievementController::class, 'show']);

Route::get('/equipments', [App\Http\Controllers\EquipmentController::class, 'index']);
Route::get('/equipments/{id}', [App\Http\Controllers\EquipmentController::class, 'show']);

Route::get('/our-works', [App\Http\Controllers\OurWorkController::class, 'index']);
Route::get('/our-works/{id}', [App\Http\Controllers\OurWorkController::class, 'show']);

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

    /*
    |--------------------------------------------------------------------------
    | Admin Routes (مسارات الأدمن فقط)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {

        // CRUD للجداول الخاصة بالأدمن
        Route::apiResource('achievements', AdminAchievementController::class);
        Route::apiResource('equipments', AdminEquipmentController::class);
        Route::apiResource('our-works', AdminOurWorkController::class);
        Route::apiResource('partners', AdminPartnerController::class);
        Route::apiResource('clients', AdminClientController::class);

        // إدارة معلومات المتجر
        Route::post('/store-info/update', [AdminController::class, 'updateStoreInfo']);
    });

});
