<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Product;
use App\Models\Category;
use App\Models\Returns;
use App\Models\ReturnItem;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use DB;
use Str;

// app/Http/Controllers/ReturnController.php
class ReturnController extends Controller
{
    // ReturnController.php
    public function index()
    {
        $returns = Returns::with(['transaction', 'product', 'user'])
            ->paginate(10);
        return view('return.index', compact('returns'));
    }

    public function create($transaction_id)
    {
        // Redirect ke index dengan modal auto-terbuka via session
        return redirect()->route('returns.index')
            ->with('open_return_for', $transaction_id);
    }

    public function store(Request $request)
    {
        \Log::info($request->all());

        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:500',
        ]);

        // Cek stok retur tidak melebihi pembelian
        $transactionDetail = TransactionDetail::where('transaction_id', $request->transaction_id)
            ->where('product_id', $request->product_id)
            ->firstOrFail();

        if ($request->quantity > $transactionDetail->quantity) {
            return back()->with('error', 'Jumlah retur melebihi jumlah pembelian (' . $transactionDetail->quantity . ' item).');
        }

        $number = 'RET-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        Returns::create([
            'return_number' => $number,
            'transaction_id' => $validated['transaction_id'],
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'total_refund' => $transactionDetail->price * $validated['quantity'],
            'reason' => $validated['reason'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('returns.index')
            ->with('success', 'Retur ' . $number . ' berhasil diajukan dan menunggu persetujuan.');
    }

    public function approve($id)
    {
        $return = Returns::findOrFail($id);

        // Update stok produk
        $product = $return->product;
        $product->stock += $return->quantity;
        $product->save();

        $return->update(['status' => 'approved']);

        return back()->with('success', 'Retur disetujui dan stok diperbarui');
    }

    public function reject($id)
    {
        $return = Returns::findOrFail($id);
        $return->update(['status' => 'rejected']);
        return back()->with('success', 'Retur ditolak');
    }
}
