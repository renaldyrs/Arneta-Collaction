<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockOpnameController extends Controller
{
    public function index()
    {
        $opnames = StockOpname::with('user')->orderBy('created_at', 'desc')->paginate(15);
        return view('stock_opnames.index', compact('opnames'));
    }

    public function create()
    {
        return view('stock_opnames.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'opname_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $opname = StockOpname::create([
                'reference_number' => 'SO-' . strtoupper(Str::random(8)),
                'opname_date' => $request->opname_date,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Fetch all products with their sizes
            $products = Product::with('sizes')->get();

            foreach ($products as $product) {
                if ($product->sizes->count() > 0) {
                    foreach ($product->sizes as $size) {
                        StockOpnameDetail::create([
                            'stock_opname_id' => $opname->id,
                            'product_id' => $product->id,
                            'size_id' => $size->id,
                            'system_stock' => $size->pivot->stock,
                            'physical_stock' => $size->pivot->stock,
                            'adjustment_quantity' => 0,
                        ]);
                    }
                } else {
                    StockOpnameDetail::create([
                        'stock_opname_id' => $opname->id,
                        'product_id' => $product->id,
                        'size_id' => null,
                        'system_stock' => $product->stock,
                        'physical_stock' => $product->stock,
                        'adjustment_quantity' => 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('stock-opnames.show', $opname->id)->with('success', 'Sesi Stock Opname berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat sesi: ' . $e->getMessage());
        }
    }

    public function show(StockOpname $stockOpname)
    {
        $stockOpname->load('details.product', 'details.size');
        return view('stock_opnames.show', compact('stockOpname'));
    }

    public function update(Request $request, StockOpname $stockOpname)
    {
        if ($stockOpname->status !== 'pending') {
            return back()->with('error', 'Hanya sesi pending yang dapat diubah.');
        }

        $request->validate([
            'physical_stocks' => 'required|array',
            'physical_stocks.*' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->physical_stocks as $detailId => $physicalStock) {
                $detail = StockOpnameDetail::findOrFail($detailId);
                $detail->update([
                    'physical_stock' => $physicalStock,
                    'adjustment_quantity' => $physicalStock - $detail->system_stock,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Stok fisik berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui stok: ' . $e->getMessage());
        }
    }

    public function complete(StockOpname $stockOpname)
    {
        if ($stockOpname->status !== 'pending') {
            return back()->with('error', 'Sesi sudah selesai atau dibatalkan.');
        }

        try {
            DB::beginTransaction();

            foreach ($stockOpname->details as $detail) {
                if ($detail->size_id) {
                    $product = Product::findOrFail($detail->product_id);
                    $product->sizes()->updateExistingPivot($detail->size_id, [
                        'stock' => $detail->physical_stock
                    ]);
                    
                    // Update main product stock sum
                    $totalStock = DB::table('product_sizes')->where('product_id', $detail->product_id)->sum('stock');
                    $product->update(['stock' => $totalStock]);
                } else {
                    Product::where('id', $detail->product_id)->update([
                        'stock' => $detail->physical_stock
                    ]);
                }
            }

            $stockOpname->update(['status' => 'completed']);

            DB::commit();
            return redirect()->route('stock-opnames.index')->with('success', 'Stock Opname berhasil diselesaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan sesi: ' . $e->getMessage());
        }
    }

    public function destroy(StockOpname $stockOpname)
    {
        if ($stockOpname->status === 'completed') {
            return back()->with('error', 'Sesi yang sudah selesai tidak dapat dihapus.');
        }
        $stockOpname->delete();
        return redirect()->route('stock-opnames.index')->with('success', 'Sesi Stock Opname berhasil dihapus.');
    }
}
