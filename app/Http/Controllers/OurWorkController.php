<?php

namespace App\Http\Controllers;

use App\Models\OurWork;

class OurWorkController extends Controller
{
    public function index()
    {
        return response()->json(
            OurWork::latest()->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            OurWork::findOrFail($id)
        );
    }
}
