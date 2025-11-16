<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('customer')->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'message' => 'Payment list',
            'data'    => $payments,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'  => ['required', 'exists:customers,id'],
            'amount'       => ['required', 'numeric', 'min:0'],
            'currency'     => ['nullable', 'string', 'size:3'],
            'period_start' => ['nullable', 'date'],
            'period_end'   => ['nullable', 'date'],
            'paid_at'      => ['nullable', 'date'],
            'method'       => ['nullable', 'string', 'max:50'],
            'status'       => ['nullable', 'in:paid,pending,failed'],
            'notes'        => ['nullable', 'string'],
        ]);

        $payment = Payment::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Payment created',
            'data'    => $payment,
        ], 201);
    }

    public function show(Payment $payment)
    {
        return response()->json([
            'success' => true,
            'message' => 'Payment detail',
            'data'    => $payment->load('customer'),
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'customer_id'  => ['sometimes', 'required', 'exists:customers,id'],
            'amount'       => ['sometimes', 'required', 'numeric', 'min:0'],
            'currency'     => ['nullable', 'string', 'size:3'],
            'period_start' => ['nullable', 'date'],
            'period_end'   => ['nullable', 'date'],
            'paid_at'      => ['nullable', 'date'],
            'method'       => ['nullable', 'string', 'max:50'],
            'status'       => ['nullable', 'in:paid,pending,failed'],
            'notes'        => ['nullable', 'string'],
        ]);

        $payment->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Payment updated',
            'data'    => $payment,
        ]);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted',
        ]);
    }
}
