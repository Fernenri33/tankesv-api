<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('customer')->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Store list',
            'data'    => $stores,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'name'        => ['required', 'string', 'max:255'],
            'domain'      => ['nullable', 'string', 'max:255', 'unique:stores,domain'],
            'logo'        => ['nullable', 'string', 'max:255'],
            'settings'    => ['nullable', 'array'],
            'status'      => ['nullable', 'in:active,suspended'],
        ]);

        $store = Store::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Store created',
            'data'    => $store,
        ], 201);
    }

    public function show(Store $store)
    {
        return response()->json([
            'success' => true,
            'message' => 'Store detail',
            'data'    => $store->load('customer'),
        ]);
    }

    public function update(Request $request, Store $store)
    {
        $data = $request->validate([
            'customer_id' => ['sometimes', 'required', 'exists:customers,id'],
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'domain'      => ['nullable', 'string', 'max:255', 'unique:stores,domain,' . $store->id],
            'logo'        => ['nullable', 'string', 'max:255'],
            'settings'    => ['nullable', 'array'],
            'status'      => ['nullable', 'in:active,suspended'],
        ]);

        $store->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Store updated',
            'data'    => $store,
        ]);
    }

    public function destroy(Store $store)
    {
        $store->delete();

        return response()->json([
            'success' => true,
            'message' => 'Store deleted',
        ]);
    }
}
