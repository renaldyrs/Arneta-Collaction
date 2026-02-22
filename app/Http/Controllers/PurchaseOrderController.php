<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderDetail;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $supplier = $request->input('supplier_id');

        $query = PurchaseOrder::with(['supplier', 'user']);

        if ($search) {
            $query->where('po_number', 'like', "%{$search}%");
        }
        if ($status) {
            $query->where('status', $status);
        }
        if ($supplier) {
            $query->where('supplier_id', $supplier);
        }

        $purchaseOrders = $query->latest()->paginate(15)->withQueryString();
        $suppliers = Supplier::orderBy('name')->get();

        $totalOrders = PurchaseOrder::count();
        $pendingOrders = PurchaseOrder::where('status', 'pending')->count();
        $totalValue = PurchaseOrder::sum('total_amount');

        return view('purchase-orders.index', compact(
            'purchaseOrders',
            'suppliers',
            'search',
            'status',
            'supplier',
            'totalOrders',
            'pendingOrders',
            'totalValue'
        ));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::with('category')->orderBy('name')->get();
        return view('purchase-orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['quantity'] * $item['unit_price'];
            }

            $po = PurchaseOrder::create([
                'po_number' => PurchaseOrder::generatePoNumber(),
                'supplier_id' => $request->supplier_id,
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total_amount' => $total,
                'notes' => $request->notes,
                'expected_date' => $request->expected_date,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $po->details()->create([
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'quantity_received' => 0,
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            ActivityLog::log('created', "Purchase Order '{$po->po_number}' dibuat", $po);
            DB::commit();

            Alert::success('Berhasil', 'Purchase Order berhasil dibuat.');
            return redirect()->route('purchase-orders.show', $po);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'user', 'details.product.category']);
        return view('purchase-orders.show', compact('purchaseOrder'));
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'Hanya PO berstatus Pending yang bisa diubah.');
        }
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::with('category')->orderBy('name')->get();
        return view('purchase-orders.edit', compact('purchaseOrder', 'suppliers', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'Hanya PO berstatus Pending yang bisa diubah.');
        }

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($request->items as $item) {
                $total += $item['quantity'] * $item['unit_price'];
            }

            $purchaseOrder->update([
                'supplier_id' => $request->supplier_id,
                'total_amount' => $total,
                'notes' => $request->notes,
                'expected_date' => $request->expected_date,
            ]);

            $purchaseOrder->details()->delete();
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $purchaseOrder->details()->create([
                    'product_id' => $item['product_id'],
                    'quantity_ordered' => $item['quantity'],
                    'quantity_received' => 0,
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            ActivityLog::log('updated', "Purchase Order '{$purchaseOrder->po_number}' diubah", $purchaseOrder);
            DB::commit();

            Alert::success('Berhasil', 'Purchase Order berhasil diperbarui.');
            return redirect()->route('purchase-orders.show', $purchaseOrder);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    // Ubah status ke "ordered"
    public function markOrdered(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'Status tidak valid.');
        }
        $purchaseOrder->update(['status' => 'ordered']);
        ActivityLog::log('updated', "PO '{$purchaseOrder->po_number}' ditandai sebagai Dipesan", $purchaseOrder);

        Alert::success('Berhasil', 'PO berhasil ditandai sebagai Dipesan.');
        return back();
    }

    // Terima barang & update stok
    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if (!in_array($purchaseOrder->status, ['pending', 'ordered'])) {
            return back()->with('error', 'PO sudah selesai atau dibatalkan.');
        }

        $request->validate([
            'received' => 'required|array',
            'received.*.quantity_received' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->received as $detailId => $data) {
                $detail = PurchaseOrderDetail::findOrFail($detailId);
                $qty = (int) $data['quantity_received'];

                if ($qty > 0) {
                    $detail->update(['quantity_received' => $qty]);
                    // Update stok produk
                    $detail->product->increment('stock', $qty);
                }
            }

            $purchaseOrder->update([
                'status' => 'received',
                'received_date' => now()->toDateString(),
            ]);

            ActivityLog::log('approved', "Barang PO '{$purchaseOrder->po_number}' diterima & stok diperbarui", $purchaseOrder);
            DB::commit();

            Alert::success('Berhasil', 'Barang diterima dan stok berhasil diperbarui.');
            return redirect()->route('purchase-orders.show', $purchaseOrder);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Batalkan PO
    public function cancel(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'received') {
            return back()->with('error', 'PO yang sudah diterima tidak bisa dibatalkan.');
        }
        $purchaseOrder->update(['status' => 'cancelled']);
        ActivityLog::log('deleted', "PO '{$purchaseOrder->po_number}' dibatalkan", $purchaseOrder);

        Alert::success('Berhasil', 'PO berhasil dibatalkan.');
        return back();
    }
}
