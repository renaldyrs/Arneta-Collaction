<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::latest()->paginate(10);
        $totalExpenses = Expense::sum('amount');
        $categories = ['Bahan Baku', 'Operasional', 'Gaji', 'Lainnya'];

        return view('expense.index', compact('expenses', 'totalExpenses', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $expense = Expense::create([
            'date' => $validated['date'],
            'amount' => $validated['amount'],
            'category' => $validated['category'],
            'description' => $validated['description'] ?? null,
            'user_id' => auth()->id(),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'expense' => $expense], 201);
        }

        return back()->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Pengeluaran berhasil dihapus.');
    }
}