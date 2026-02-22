<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Transaction;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $date = $request->input('date');
        $userId = $request->input('user_id');

        $query = Shift::with('user');

        if ($date) {
            $query->whereDate('created_at', $date);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }

        $shifts = $query->latest()->paginate(15)->withQueryString();
        $activeShift = Shift::getActiveShift(auth()->id());

        // Statistik
        $totalShifts = Shift::whereDate('created_at', today())->count();
        $openShifts = Shift::where('status', 'open')->count();
        $totalRevenue = Shift::whereDate('created_at', today())->sum('total_revenue');

        return view('shifts.index', compact(
            'shifts',
            'activeShift',
            'totalShifts',
            'openShifts',
            'totalRevenue'
        ));
    }

    // Buka shift baru
    public function open(Request $request)
    {
        // Cek apakah ada shift aktif
        $existing = Shift::getActiveShift(auth()->id());
        if ($existing) {
            return back()->with('error', 'Anda masih memiliki shift yang aktif. Tutup shift terlebih dahulu.');
        }

        $request->validate([
            'opening_cash' => 'required|numeric|min:0',
            'opening_notes' => 'nullable|string|max:500',
        ]);

        $shift = Shift::create([
            'shift_number' => Shift::generateShiftNumber(),
            'user_id' => auth()->id(),
            'opening_cash' => $request->opening_cash,
            'opening_notes' => $request->opening_notes,
            'status' => 'open',
            'opened_at' => now(),
        ]);

        ActivityLog::log('created', "Shift '{$shift->shift_number}' dibuka", $shift);

        Alert::success('Shift Dibuka', "Shift {$shift->shift_number} berhasil dibuka.");
        return redirect()->route('shifts.show', $shift);
    }

    public function show(Shift $shift)
    {
        $shift->load(['user', 'transactions.paymentMethod']);

        // Hitung statistik shift
        $cashTransactions = $shift->transactions()->whereHas('paymentMethod', function ($q) {
            $q->where('name', 'like', '%tunai%')->orWhere('name', 'like', '%cash%');
        })->sum('total_amount');

        $revenueByPayment = $shift->transactions()
            ->with('paymentMethod')
            ->get()
            ->groupBy('payment_method_id')
            ->map(function ($items) {
                return [
                    'name' => $items->first()->paymentMethod->name ?? 'Unknown',
                    'count' => $items->count(),
                    'total' => $items->sum('total_amount'),
                ];
            });

        return view('shifts.show', compact('shift', 'cashTransactions', 'revenueByPayment'));
    }

    // Tutup shift
    public function close(Request $request, Shift $shift)
    {
        if ($shift->status !== 'open') {
            return back()->with('error', 'Shift sudah ditutup.');
        }
        if ($shift->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki akses untuk menutup shift ini.');
        }

        $request->validate([
            'closing_cash' => 'required|numeric|min:0',
            'closing_notes' => 'nullable|string|max:500',
        ]);

        // Hitung statistik
        $totalTransactions = $shift->transactions()->count();
        $totalRevenue = $shift->transactions()->sum('total_amount');
        $expectedCash = $shift->opening_cash + $totalRevenue;
        $difference = $request->closing_cash - $expectedCash;

        $shift->update([
            'closing_cash' => $request->closing_cash,
            'closing_notes' => $request->closing_notes,
            'expected_cash' => $expectedCash,
            'cash_difference' => $difference,
            'total_transactions' => $totalTransactions,
            'total_revenue' => $totalRevenue,
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        ActivityLog::log('updated', "Shift '{$shift->shift_number}' ditutup. Selisih: Rp " . number_format($difference, 0, ',', '.'), $shift);

        Alert::success('Shift Ditutup', "Shift {$shift->shift_number} berhasil ditutup.");
        return redirect()->route('shifts.show', $shift);
    }
}
