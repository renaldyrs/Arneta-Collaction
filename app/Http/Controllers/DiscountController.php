<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class DiscountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin'])->except('validate_code');
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Discount::withCount('transactions');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%");
        }

        $discounts = $query->latest()->paginate(15)->withQueryString();

        $totalDiscounts = Discount::count();
        $activeDiscounts = Discount::active()->count();
        $totalUsed = Discount::sum('used_count');

        return view('discounts.index', compact(
            'discounts',
            'search',
            'totalDiscounts',
            'activeDiscounts',
            'totalUsed'
        ));
    }

    public function create()
    {
        return redirect()->route('discounts.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:discounts,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->type === 'percentage' && $request->value > 100) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['errors' => ['value' => ['Persentase diskon tidak boleh lebih dari 100%']]], 422);
            }
            return back()->withErrors(['value' => 'Persentase diskon tidak boleh lebih dari 100%'])->withInput();
        }

        $data = $request->all();
        $data['is_active'] = (bool) $request->input('is_active', false);
        $data['code'] = $request->code ? strtoupper($request->code) : null;

        $discount = Discount::create($data);
        ActivityLog::log('created', "Diskon '{$discount->name}' berhasil ditambahkan", $discount);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'discount' => $discount], 201);
        }

        Alert::success('Berhasil', 'Diskon berhasil ditambahkan.');
        return redirect()->route('discounts.index');
    }

    public function edit(Discount $discount)
    {
        if (request()->wantsJson()) {
            return response()->json($discount);
        }
        return redirect()->route('discounts.index');
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:discounts,code,' . $discount->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0.01',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->type === 'percentage' && $request->value > 100) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['errors' => ['value' => ['Persentase diskon tidak boleh lebih dari 100%']]], 422);
            }
            return back()->withErrors(['value' => 'Persentase diskon tidak boleh lebih dari 100%'])->withInput();
        }

        $old = $discount->toArray();
        $data = $request->all();
        $data['is_active'] = (bool) $request->input('is_active', false);
        $data['code'] = $request->code ? strtoupper($request->code) : null;

        $discount->update($data);
        ActivityLog::log('updated', "Diskon '{$discount->name}' diubah", $discount, $old, $discount->toArray());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'discount' => $discount->fresh()]);
        }

        Alert::success('Berhasil', 'Diskon berhasil diperbarui.');
        return redirect()->route('discounts.index');
    }

    public function destroy(Discount $discount)
    {
        ActivityLog::log('deleted', "Diskon '{$discount->name}' dihapus", $discount);
        $discount->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        Alert::success('Berhasil', 'Diskon berhasil dihapus.');
        return redirect()->route('discounts.index');
    }

    // API: Validasi kode diskon (untuk kasir)
    public function validate_code(Request $request)
    {
        $code = $request->input('code');
        $amount = $request->input('amount', 0);

        $discount = Discount::where('code', strtoupper($code))->first();

        if (!$discount) {
            return response()->json(['valid' => false, 'message' => 'Kode diskon tidak ditemukan']);
        }

        if (!$discount->isValid($amount)) {
            $message = 'Diskon tidak berlaku';
            if ($amount < $discount->min_purchase) {
                $message = 'Minimum pembelian Rp ' . number_format((float) $discount->min_purchase, 0, ',', '.');
            } elseif ($discount->max_uses && $discount->used_count >= $discount->max_uses) {
                $message = 'Kuota diskon sudah habis';
            } elseif ($discount->end_date && now()->gt($discount->end_date)) {
                $message = 'Diskon sudah kadaluarsa';
            }
            return response()->json(['valid' => false, 'message' => $message]);
        }

        $discountAmount = $discount->calculateDiscount($amount);

        return response()->json([
            'valid' => true,
            'discount' => [
                'id' => $discount->id,
                'name' => $discount->name,
                'type' => $discount->type,
                'value' => $discount->value,
                'discount_amount' => $discountAmount,
                'formatted_value' => $discount->formatted_value,
            ]
        ]);
    }
}
