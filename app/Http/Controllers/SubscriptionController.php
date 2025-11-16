<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subs = Subscription::with(['customer', 'plan'])
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Subscription list',
            'data'    => $subs,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'     => ['required', 'exists:customers,id'],
            'plan_id'         => ['required', 'exists:plans,id'],
            'status'          => ['nullable', 'in:trial,active,suspended,cancelled'],
            'trial_starts_at' => ['nullable', 'date'],
            'trial_ends_at'   => ['nullable', 'date'],
            'starts_at'       => ['nullable', 'date'],
            'ends_at'         => ['nullable', 'date'],
            'last_renewed_at' => ['nullable', 'date'],
            'cancelled_at'    => ['nullable', 'date'],
            'notes'           => ['nullable', 'string'],
        ]);

        $sub = Subscription::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Subscription created',
            'data'    => $sub,
        ], 201);
    }

    public function show(Subscription $subscription)
    {
        return response()->json([
            'success' => true,
            'message' => 'Subscription detail',
            'data'    => $subscription->load(['customer', 'plan']),
        ]);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'customer_id'     => ['sometimes', 'required', 'exists:customers,id'],
            'plan_id'         => ['sometimes', 'required', 'exists:plans,id'],
            'status'          => ['nullable', 'in:trial,active,suspended,cancelled'],
            'trial_starts_at' => ['nullable', 'date'],
            'trial_ends_at'   => ['nullable', 'date'],
            'starts_at'       => ['nullable', 'date'],
            'ends_at'         => ['nullable', 'date'],
            'last_renewed_at' => ['nullable', 'date'],
            'cancelled_at'    => ['nullable', 'date'],
            'notes'           => ['nullable', 'string'],
        ]);

        $subscription->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Subscription updated',
            'data'    => $subscription,
        ]);
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscription deleted',
        ]);
    }
}
