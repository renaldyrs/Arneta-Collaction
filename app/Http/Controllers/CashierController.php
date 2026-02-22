<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\PaymentMethod;
use App\Models\Category;
use App\Models\StoreProfile;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Shift;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CashierController extends Controller
{
    public function index()
    {
        $products = Product::with('sizes')
            ->where('stock', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();
        $storeProfile = StoreProfile::first();
        $paymentMethods = PaymentMethod::all();
        $categories = Category::all();
        $activeShift = Shift::getActiveShift(auth()->id());
        $activeDiscounts = Discount::active()->get();

        return view('cashier.index', compact(
            'products',
            'paymentMethods',
            'storeProfile',
            'categories',
            'activeShift',
            'activeDiscounts'
        ));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validasi request
            $validator = Validator::make($request->all(), [
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'payment_amount' => 'required|numeric|min:0',
                'customer_id' => 'nullable|exists:customers,id',
                'discount_id' => 'nullable|exists:discounts,id',
                'discount_code' => 'nullable|string',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Cek stok semua produk
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                if (!$product) {
                    throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan");
                }
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi. Tersedia: {$product->stock}");
                }
            }

            // Hitung subtotal
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            // Proses diskon
            $discountAmount = 0;
            $discountId = $request->discount_id;

            if ($discountId) {
                $discount = Discount::find($discountId);
                if ($discount && $discount->isValid($subtotal)) {
                    $discountAmount = $discount->calculateDiscount($subtotal);
                    $discount->increment('used_count');
                }
            }

            $total = $subtotal - $discountAmount;
            $paymentAmount = $request->payment_amount;
            $changeAmount = $paymentAmount - $total;

            if ($changeAmount < 0) {
                throw new \Exception("Uang bayar kurang dari total transaksi");
            }

            // Cek shift aktif
            $activeShift = Shift::getActiveShift(auth()->id());

            // Buat transaksi
            $transaction = Transaction::create([
                'invoice_number' => Transaction::generateInvoiceNumber(),
                'total_amount' => $total,
                'payment_method_id' => $request->payment_method_id,
                'payment_amount' => $paymentAmount,
                'change_amount' => $changeAmount,
                'discount_amount' => $discountAmount,
                'discount_id' => $discountId,
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id(),
                'shift_id' => $activeShift ? $activeShift->id : null,
                'notes' => $request->notes,
                'transaction_date' => now(),
            ]);

            // Simpan details dan update stok
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                $subtotalItem = $product->price * $item['quantity'];

                $transaction->details()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotalItem,
                    'size' => $item['size'] ?? null,
                ]);

                $product->stock -= $item['quantity'];
                $product->save();
            }

            // Update poin pelanggan (1 poin per Rp 10.000)
            if ($request->customer_id) {
                $customer = Customer::find($request->customer_id);
                if ($customer) {
                    $pointsEarned = (int) ($total / 10000);
                    $customer->increment('points', $pointsEarned);
                    $customer->increment('total_spent', $total);
                }
            }

            // Log aktivitas
            ActivityLog::log('created', "Transaksi {$transaction->invoice_number} berhasil. Total: Rp " . number_format($total, 0, ',', '.'), $transaction);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diproses',
                'transaction' => $transaction->load('details.product', 'paymentMethod', 'customer')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction Error: ' . $e->getMessage());
            Log::error('Transaction Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function print($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'customer'])->findOrFail($id);
        $storeProfile = StoreProfile::first();
        return view('cashier.print', compact('transaction', 'storeProfile'));
    }

    public function invoice($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'customer', 'discount'])->findOrFail($id);
        $storeProfile = StoreProfile::first();
        return view('cashier.invoice', compact('transaction', 'storeProfile'));
    }

    public function printInvoice($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'customer'])->findOrFail($id);
        $storeProfile = StoreProfile::first();
        return view('cashier.print-invoice', compact('transaction', 'storeProfile'));
    }

    public function orders()
    {
        $transactions = Transaction::with(['user', 'paymentMethod', 'customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('cashier.orders', compact('transactions'));
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'user', 'customer'])->findOrFail($id);
        $storeProfile = StoreProfile::first();
        return view('cashier.print', compact('transaction', 'storeProfile'));
    }

    public function showReceipt($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'user', 'customer', 'discount'])->findOrFail($id);
        $storeProfile = StoreProfile::first();

        if (request()->wantsJson() || request()->ajax()) {
            return view('cashier.receipt-content', compact('transaction', 'storeProfile'))->render();
        }

        return view('cashier.receipt', compact('transaction', 'storeProfile'));
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Produk tidak ditemukan'], 404);
        }
        if ($product->stock <= 0) {
            return response()->json(['success' => false, 'message' => 'Stok habis'], 400);
        }
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'stock' => $product->stock,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
            ]
        ]);
    }
}