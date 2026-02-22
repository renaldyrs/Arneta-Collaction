<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\PaymentMethod;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // ── Filter params ──────────────────────────────────────────
        $search = $request->input('search');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $paymentId = $request->input('payment_method_id');
        $customerId = $request->input('customer_id');
        $kasirId = $request->input('user_id');
        $sort = $request->input('sort', 'newest');

        // ── Query ──────────────────────────────────────────────────
        $query = Transaction::with(['details.product', 'paymentMethod', 'customer', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }
        if ($paymentId)
            $query->where('payment_method_id', $paymentId);
        if ($customerId)
            $query->where('customer_id', $customerId);
        if ($kasirId && auth()->user()->role === 'admin') {
            $query->where('user_id', $kasirId);
        } elseif (auth()->user()->role !== 'admin') {
            // Kasir hanya lihat transaksi miliknya sendiri
            $query->where('user_id', auth()->id());
        }

        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'highest') {
            $query->orderBy('total_amount', 'desc');
        } elseif ($sort === 'lowest') {
            $query->orderBy('total_amount', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $transactions = $query->paginate(15)->withQueryString();

        // ── Stats (tidak dipengaruhi paginate) ────────────────────
        $statsQuery = Transaction::whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
        if (auth()->user()->role !== 'admin') {
            $statsQuery->where('user_id', auth()->id());
        }
        $totalRevenue = $statsQuery->sum('total_amount');
        $totalTransactions = $statsQuery->count();
        $totalDiscount = $statsQuery->sum('discount_amount');
        $avgTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // ── Dropdown data ─────────────────────────────────────────
        $paymentMethods = PaymentMethod::all();
        $customers = Customer::orderBy('name')->get();
        $kasirList = auth()->user()->role === 'admin'
            ? User::orderBy('name')->get()
            : collect();

        return view('transactions.index', compact(
            'transactions',
            'totalRevenue',
            'totalTransactions',
            'totalDiscount',
            'avgTransaction',
            'paymentMethods',
            'customers',
            'kasirList',
            'search',
            'startDate',
            'endDate',
            'paymentId',
            'customerId',
            'kasirId',
            'sort'
        ));
    }

    /**
     * Export CSV riwayat transaksi
     */
    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $paymentId = $request->input('payment_method_id');
        $customerId = $request->input('customer_id');
        $search = $request->input('search');

        $query = Transaction::with(['details.product', 'paymentMethod', 'customer', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc');

        if ($search)
            $query->where('invoice_number', 'like', "%{$search}%");
        if ($paymentId)
            $query->where('payment_method_id', $paymentId);
        if ($customerId)
            $query->where('customer_id', $customerId);
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        $transactions = $query->get();
        $filename = 'riwayat_transaksi_' . $startDate . '_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transactions, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // BOM UTF-8

            fputcsv($handle, ['RIWAYAT TRANSAKSI']);
            fputcsv($handle, ['Periode', $startDate . ' s/d ' . $endDate]);
            fputcsv($handle, ['Diekspor pada', now()->format('d/m/Y H:i')]);
            fputcsv($handle, []);

            $totalRevenue = $transactions->sum('total_amount');
            $totalDiscount = $transactions->sum('discount_amount');
            fputcsv($handle, ['--- RINGKASAN ---']);
            fputcsv($handle, ['Total Transaksi', $transactions->count()]);
            fputcsv($handle, ['Total Pendapatan', number_format($totalRevenue, 2, '.', '')]);
            fputcsv($handle, ['Total Diskon', number_format($totalDiscount, 2, '.', '')]);
            fputcsv($handle, []);

            fputcsv($handle, [
                'No. Invoice',
                'Tanggal',
                'Kasir',
                'Pelanggan',
                'Produk',
                'Total Item',
                'Metode Bayar',
                'Subtotal',
                'Diskon',
                'Total',
                'Uang Bayar',
                'Kembalian',
            ]);

            foreach ($transactions as $trx) {
                $items = $trx->details->map(fn($d) => $d->product->name . ' x' . $d->quantity)->implode(' | ');
                $totalQty = $trx->details->sum('quantity');
                fputcsv($handle, [
                    $trx->invoice_number,
                    $trx->created_at->format('d/m/Y H:i'),
                    $trx->user->name ?? '-',
                    $trx->customer->name ?? 'Umum',
                    $items,
                    $totalQty,
                    $trx->paymentMethod->name ?? '-',
                    number_format($trx->total_amount + $trx->discount_amount, 2, '.', ''),
                    number_format($trx->discount_amount, 2, '.', ''),
                    number_format($trx->total_amount, 2, '.', ''),
                    number_format($trx->payment_amount, 2, '.', ''),
                    number_format($trx->change_amount, 2, '.', ''),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
