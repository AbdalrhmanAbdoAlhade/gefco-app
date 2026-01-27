<?php

namespace App\Http\Controllers;

use App\Models\Achievement;

class AchievementController extends Controller
{
    public function index()
    {
        return response()->json(
            Achievement::latest()->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Achievement::findOrFail($id)
        );
    }
}
