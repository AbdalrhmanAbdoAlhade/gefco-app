<?php

namespace App\Http\Controllers;

use App\Models\Partner;

class PartnerController extends Controller
{
    public function index()
    {
        return response()->json(
            Partner::latest()->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Partner::findOrFail($id)
        );
    }
}
