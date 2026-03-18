<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PurchaseOrder;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $po = PurchaseOrder::findOrFail($request->purchase_order_id);
            
            $remaining = $po->total_amount - $po->paid_amount;
            if ($request->amount > ($remaining + 0.01)) { // Small buffer for floats
                return back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan.');
            }

            PurchasePayment::create($request->all());

            $po->paid_amount += $request->amount;
            $po->payment_status = $po->paid_amount >= $po->total_amount ? 'paid' : 'partial';
            $po->save();

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }

    public function destroy(PurchasePayment $purchasePayment)
    {
        try {
            DB::beginTransaction();

            $po = $purchasePayment->purchaseOrder;
            $po->paid_amount -= $purchasePayment->amount;
            $po->payment_status = $po->paid_amount <= 0 ? 'unpaid' : ($po->paid_amount < $po->total_amount ? 'partial' : 'paid');
            $po->save();
            
            $purchasePayment->delete();

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pembayaran.');
        }
    }
}
