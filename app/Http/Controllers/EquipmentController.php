<?php

namespace App\Http\Controllers;

use App\Models\Equipment;

class EquipmentController extends Controller
{
    public function index()
    {
        return response()->json(
            Equipment::latest()->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Equipment::findOrFail($id)
        );
    }
}
