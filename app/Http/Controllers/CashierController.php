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
use App\Models\Size;
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
        $printSetting = \App\Models\PrintSetting::first();
        $paymentMethods = PaymentMethod::all();
        $categories = Category::all();
        $activeShift = Shift::getActiveShift(auth()->id());
        $activeDiscounts = Discount::active()->get();

        return view('cashier.index', compact(
            'products',
            'paymentMethods',
            'storeProfile',
            'printSetting',
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

            // Cek stok semua produk (jika item memiliki ukuran, cek stok ukuran)
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                if (!$product) {
                    throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan");
                }

                // Jika item memiliki ukuran spesifik, cek stok pada pivot product_sizes
                if (!empty($item['size'])) {
                    $sizeModel = Size::where('name', $item['size'])->first();
                    if (!$sizeModel) {
                        throw new \Exception("Ukuran {$item['size']} untuk produk {$product->name} tidak ditemukan");
                    }
                    $pivot = DB::table('product_sizes')
                        ->where('product_id', $product->id)
                        ->where('size_id', $sizeModel->id)
                        ->first();
                    $available = $pivot ? (int) $pivot->stock : 0;
                    if ($available < $item['quantity']) {
                        throw new \Exception("Stok ukuran {$item['size']} untuk produk {$product->name} tidak mencukupi. Tersedia: {$available}");
                    }
                } else {
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi. Tersedia: {$product->stock}");
                    }
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

                // Jika transaksi untuk ukuran tertentu, kurangi stok pada pivot product_sizes
                if (!empty($item['size'])) {
                    $sizeModel = Size::where('name', $item['size'])->first();
                    if ($sizeModel) {
                        $pivot = DB::table('product_sizes')
                            ->where('product_id', $product->id)
                            ->where('size_id', $sizeModel->id)
                            ->first();
                        $newStock = 0;
                        if ($pivot) {
                            $newStock = max(0, (int) $pivot->stock - (int) $item['quantity']);
                            // update pivot
                            DB::table('product_sizes')
                                ->where('product_id', $product->id)
                                ->where('size_id', $sizeModel->id)
                                ->update(['stock' => $newStock, 'updated_at' => now()]);
                        }

                        // Recompute total product stock from all sizes
                        $totalStock = DB::table('product_sizes')
                            ->where('product_id', $product->id)
                            ->sum('stock');
                        $product->stock = (int) $totalStock;
                        $product->save();
                    } else {
                        // fallback: kurangi stok produk utama jika ukuran tidak ditemukan
                        $product->stock = max(0, $product->stock - (int) $item['quantity']);
                        $product->save();
                    }
                } else {
                    $product->stock -= $item['quantity'];
                    $product->save();
                }
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
        $printSetting = \App\Models\PrintSetting::first();
        return view('cashier.print', compact('transaction', 'storeProfile', 'printSetting'));
    }

    public function invoice($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'customer', 'discount'])->findOrFail($id);
        $storeProfile = StoreProfile::first();
        $printSetting = \App\Models\PrintSetting::first();
        return view('cashier.invoice', compact('transaction', 'storeProfile', 'printSetting'));
    }

    public function printInvoice($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'customer'])->findOrFail($id);
        $storeProfile = StoreProfile::first();
        $printSetting = \App\Models\PrintSetting::first();
        return view('cashier.print-invoice', compact('transaction', 'storeProfile', 'printSetting'));
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
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'user', 'customer', 'discount'])->findOrFail($id);
        $storeProfile = StoreProfile::first();
        $printSetting = \App\Models\PrintSetting::first();
        return view('cashier.print', compact('transaction', 'storeProfile', 'printSetting'))->with('receipt', true);
    }

    public function showReceipt($id)
    {
        $transaction = Transaction::with(['details.product', 'paymentMethod', 'user', 'customer', 'discount'])->findOrFail($id);
        $storeProfile = StoreProfile::first();
        $printSetting = \App\Models\PrintSetting::first();
 
        if (request()->wantsJson() || request()->ajax()) {
            return view('cashier.receipt-content', compact('transaction', 'storeProfile', 'printSetting'))->render();
        }
 
        return view('cashier.receipt', compact('transaction', 'storeProfile', 'printSetting'));
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

    public function holdCart(Request $request)
    {
        try {
            DB::beginTransaction();

            $pending = \App\Models\PendingTransaction::create([
                'cart_name' => $request->cart_name ?: 'Antrian #' . ((\App\Models\PendingTransaction::whereDate('created_at', now())->count()) + 1),
                'user_id' => auth()->id(),
                'customer_id' => $request->customer_id,
                'payment_method_id' => $request->payment_method_id,
                'discount_id' => $request->discount_id,
                'total_amount' => $request->total_amount,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $pending->details()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'size' => $item['size'] ?? null,
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pesanan berhasil ditahan']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Hold Cart Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menahan pesanan: ' . $e->getMessage()], 500);
        }
    }

    public function getHoldCarts()
    {
        $carts = \App\Models\PendingTransaction::with('details.product', 'customer')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        return response()->json(['success' => true, 'carts' => $carts]);
    }

    public function resumeCart($id)
    {
        try {
            $cart = \App\Models\PendingTransaction::with('details.product')->findOrFail($id);
            $cartData = [
                'customer_id' => $cart->customer_id,
                'discount_id' => $cart->discount_id,
                'notes' => $cart->notes,
                'items' => $cart->details->map(function($detail) {
                    return [
                        'id' => $detail->product_id,
                        'name' => $detail->product->name,
                        'price' => (float)$detail->price,
                        'quantity' => (int)$detail->quantity,
                        'size' => $detail->size,
                        'image' => $detail->product->image ? asset('storage/' . $detail->product->image) : null,
                        'stock' => (int)$detail->product->stock,
                    ];
                })
            ];
            
            $cart->delete();
            return response()->json(['success' => true, 'cart' => $cartData]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memanggil pesanan: ' . $e->getMessage()], 500);
        }
    }

    public function removeHoldCart($id)
    {
        try {
            \App\Models\PendingTransaction::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Antrian berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus antrian'], 500);
        }
    }
}