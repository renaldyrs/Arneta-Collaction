<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return view('payment.index', compact('paymentMethods'));
    }

    public function create()
    {
        return redirect()->route('payment.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name',
            'description' => 'nullable|string|max:255',
        ]);

        $paymentMethod = PaymentMethod::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'paymentMethod' => $paymentMethod], 201);
        }

        return redirect()->route('payment.index')->with('success', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        if (request()->wantsJson()) {
            return response()->json($paymentMethod);
        }
        return view('payment.show', compact('paymentMethod'));
    }

    public function edit(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        if (request()->wantsJson()) {
            return response()->json($paymentMethod);
        }
        return redirect()->route('payment.index');
    }

    public function update(Request $request, string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name,' . $id,
            'description' => 'nullable|string|max:255',
        ]);

        $paymentMethod->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'paymentMethod' => $paymentMethod->fresh()]);
        }

        return redirect()->route('payment.index')->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('payment.index')->with('success', 'Metode pembayaran berhasil dihapus.');
    }
}