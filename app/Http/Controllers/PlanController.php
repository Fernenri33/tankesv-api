<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::latest()->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Plan list',
            'data'    => $plans,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['required', 'numeric', 'min:0'],
            'currency'    => ['nullable', 'string', 'size:3'],
            'limits'      => ['nullable', 'array'],
            'is_active'   => ['nullable', 'boolean'],
            'is_default'  => ['nullable', 'boolean'],
        ]);

        $plan = Plan::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Plan created',
            'data'    => $plan,
        ], 201);
    }

    public function show(Plan $plan)
    {
        return response()->json([
            'success' => true,
            'message' => 'Plan detail',
            'data'    => $plan->load('subscriptions'),
        ]);
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price'       => ['sometimes', 'required', 'numeric', 'min:0'],
            'currency'    => ['nullable', 'string', 'size:3'],
            'limits'      => ['nullable', 'array'],
            'is_active'   => ['nullable', 'boolean'],
            'is_default'  => ['nullable', 'boolean'],
        ]);

        $plan->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Plan updated',
            'data'    => $plan,
        ]);
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Plan deleted',
        ]);
    }
}
