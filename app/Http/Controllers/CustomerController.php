<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Customer list',
            'data'    => $customers,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'max:255', 'unique:customers,email'],
            'phone'  => ['nullable', 'string', 'max:50'],
            'notes'  => ['nullable', 'string'],
            'status' => ['nullable', 'in:trial,active,suspended,cancelled'],
            'trial_ends_at' => ['nullable', 'date'],
            'activated_at'  => ['nullable', 'date'],
            'cancelled_at'  => ['nullable', 'date'],
        ]);

        $customer = Customer::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer created',
            'data'    => $customer,
        ], 201);
    }

    public function show(Customer $customer)
    {
        return response()->json([
            'success' => true,
            'message' => 'Customer detail',
            'data'    => $customer->load(['store', 'subscription', 'payments']),
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name'   => ['sometimes', 'required', 'string', 'max:255'],
            'email'  => ['sometimes', 'required', 'email', 'max:255', 'unique:customers,email,' . $customer->id],
            'phone'  => ['nullable', 'string', 'max:50'],
            'notes'  => ['nullable', 'string'],
            'status' => ['nullable', 'in:trial,active,suspended,cancelled'],
            'trial_ends_at' => ['nullable', 'date'],
            'activated_at'  => ['nullable', 'date'],
            'cancelled_at'  => ['nullable', 'date'],
        ]);

        $customer->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated',
            'data'    => $customer,
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted',
        ]);
    }
}
