<?php

namespace App\Http\Controllers;

use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        return response()->json(
            Client::latest()->get()
        );
    }

    public function show($id)
    {
        return response()->json(
            Client::findOrFail($id)
        );
    }
}
