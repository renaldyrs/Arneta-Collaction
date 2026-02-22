<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Expense;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class FinancialReportController extends Controller
{
    public function index(Request $request)
    {
        // Default periode: bulan berjalan
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Validasi tanggal
        if ($startDate > $endDate) {
            return back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
        }

        // Total pendapatan
        $totalRevenue = Transaction::dateRange($startDate, $endDate)->sum('total_amount');

        // Total diskon
        $totalDiscount = Transaction::dateRange($startDate, $endDate)->sum('discount_amount');

        // Total pengeluaran
        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');

        // Laba bersih
        $netProfit = $totalRevenue - $totalExpenses;

        // Pendapatan per metode pembayaran
        $revenueByPaymentMethod = PaymentMethod::withSum([
            'transactions' => function ($query) use ($startDate, $endDate) {
                $query->dateRange($startDate, $endDate);
            }
        ], 'total_amount')->get();

        // Transaksi harian untuk grafik
        $dailyTransactions = Transaction::selectRaw('
                DATE(created_at) as date,
                COUNT(*) as transaction_count,
                SUM(total_amount) as total_amount,
                SUM(discount_amount) as total_discount
            ')
            ->dateRange($startDate, $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Produk terlaris
        $bestSellingProducts = DB::table('transaction_details')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->selectRaw('
                products.name,
                products.code,
                SUM(transaction_details.quantity) as total_sold,
                SUM(transaction_details.subtotal) as total_revenue
            ')
            ->whereBetween('transactions.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->groupBy('products.id', 'products.name', 'products.code')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // Perbandingan bulanan
        $monthlyComparison = $this->getMonthlyComparison($startDate, $endDate);

        return view('reports.financial', compact(
            'totalRevenue',
            'totalDiscount',
            'totalExpenses',
            'netProfit',
            'revenueByPaymentMethod',
            'dailyTransactions',
            'bestSellingProducts',
            'monthlyComparison',
            'startDate',
            'endDate'
        ));
    }

    private function getMonthlyComparison($startDate, $endDate)
    {
        $currentPeriod = Transaction::selectRaw('
                SUM(total_amount) as total,
                COUNT(*) as transaction_count
            ')
            ->dateRange($startDate, $endDate)
            ->first();

        $previousStart = Carbon::parse($startDate)->subMonth()->format('Y-m-d');
        $previousEnd = Carbon::parse($endDate)->subMonth()->format('Y-m-d');

        $previousPeriod = Transaction::selectRaw('
                SUM(total_amount) as total,
                COUNT(*) as transaction_count
            ')
            ->dateRange($previousStart, $previousEnd)
            ->first();

        $currentExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $previousExpenses = Expense::whereBetween('date', [$previousStart, $previousEnd])->sum('amount');

        return [
            'current' => [
                'revenue' => $currentPeriod->total ?? 0,
                'transactions' => $currentPeriod->transaction_count ?? 0,
                'expenses' => $currentExpenses,
                'profit' => ($currentPeriod->total ?? 0) - $currentExpenses,
            ],
            'previous' => [
                'revenue' => $previousPeriod->total ?? 0,
                'transactions' => $previousPeriod->transaction_count ?? 0,
                'expenses' => $previousExpenses,
                'profit' => ($previousPeriod->total ?? 0) - $previousExpenses,
            ],
            'revenue_change' => $currentPeriod->total && $previousPeriod->total
                ? (($currentPeriod->total - $previousPeriod->total) / $previousPeriod->total) * 100 : 0,
            'transaction_change' => $currentPeriod->transaction_count && $previousPeriod->transaction_count
                ? (($currentPeriod->transaction_count - $previousPeriod->transaction_count) / $previousPeriod->transaction_count) * 100 : 0,
        ];
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $transactions = Transaction::with(['details.product', 'paymentMethod', 'customer'])
            ->dateRange($startDate, $endDate)
            ->get();

        $totalRevenue = $transactions->sum('total_amount');
        $totalDiscount = $transactions->sum('discount_amount');
        $totalExpenses = Expense::whereBetween('date', [$startDate, $endDate])->sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;

        $storeProfile = \App\Models\StoreProfile::first();

        $pdf = Pdf::loadView('reports.financial_pdf', compact(
            'transactions',
            'totalRevenue',
            'totalDiscount',
            'totalExpenses',
            'netProfit',
            'startDate',
            'endDate',
            'storeProfile'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan_keuangan_' . $startDate . '_' . $endDate . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $transactions = Transaction::with(['details.product', 'paymentMethod', 'customer', 'user'])
            ->dateRange($startDate, $endDate)
            ->get();

        $expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();

        $filename = 'laporan_keuangan_' . $startDate . '_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transactions, $expenses, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');
            // BOM untuk Excel agar baca UTF-8 dengan benar
            fputs($handle, "\xEF\xBB\xBF");

            // Header laporan
            fputcsv($handle, ['LAPORAN KEUANGAN']);
            fputcsv($handle, ['Periode', $startDate . ' s/d ' . $endDate]);
            fputcsv($handle, ['Diekspor pada', now()->format('d/m/Y H:i')]);
            fputcsv($handle, []);

            // === RINGKASAN ===
            $totalRevenue = $transactions->sum('total_amount');
            $totalDiscount = $transactions->sum('discount_amount');
            $totalExpenses = $expenses->sum('amount');
            $netProfit = $totalRevenue - $totalExpenses;

            fputcsv($handle, ['--- RINGKASAN ---']);
            fputcsv($handle, ['Total Pendapatan (Gross)', number_format($totalRevenue, 2, '.', '')]);
            fputcsv($handle, ['Total Diskon', number_format($totalDiscount, 2, '.', '')]);
            fputcsv($handle, ['Total Pengeluaran', number_format($totalExpenses, 2, '.', '')]);
            fputcsv($handle, ['Laba Bersih', number_format($netProfit, 2, '.', '')]);
            fputcsv($handle, []);

            // === DAFTAR TRANSAKSI ===
            fputcsv($handle, ['--- DAFTAR TRANSAKSI ---']);
            fputcsv($handle, [
                'No. Invoice',
                'Tanggal',
                'Kasir',
                'Pelanggan',
                'Metode Bayar',
                'Subtotal',
                'Diskon',
                'Total',
                'Uang Bayar',
                'Kembalian'
            ]);

            foreach ($transactions as $trx) {
                fputcsv($handle, [
                    $trx->invoice_number,
                    $trx->created_at->format('d/m/Y H:i'),
                    $trx->user->name ?? '-',
                    $trx->customer->name ?? '-',
                    $trx->paymentMethod->name ?? '-',
                    number_format($trx->total_amount + $trx->discount_amount, 2, '.', ''),
                    number_format($trx->discount_amount, 2, '.', ''),
                    number_format($trx->total_amount, 2, '.', ''),
                    number_format($trx->payment_amount, 2, '.', ''),
                    number_format($trx->change_amount, 2, '.', ''),
                ]);
            }

            fputcsv($handle, []);

            // === DAFTAR PENGELUARAN ===
            fputcsv($handle, ['--- DAFTAR PENGELUARAN ---']);
            fputcsv($handle, ['Tanggal', 'Keterangan', 'Jumlah']);
            foreach ($expenses as $exp) {
                fputcsv($handle, [
                    $exp->date,
                    $exp->description ?? $exp->name ?? '-',
                    number_format($exp->amount, 2, '.', ''),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}