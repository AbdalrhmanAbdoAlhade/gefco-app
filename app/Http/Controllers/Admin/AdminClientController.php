<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminClientController extends Controller
{
    public function index()
    {
        return response()->json(Client::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'logo' => 'required|image'
        ]);

        $data['logo'] = $request->file('logo')->store('clients', 'public');

        return response()->json(Client::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'logo' => 'sometimes|image'
        ]);

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($client->logo);
            $data['logo'] = $request->file('logo')->store('clients', 'public');
        }

        $client->update($data);

        return response()->json($client);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        Storage::disk('public')->delete($client->logo);
        $client->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}
