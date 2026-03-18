<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        // Get current month data
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        
        // Today's data (Filtered for cashier)
        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->count();
        $todayRevenue = Transaction::whereDate('created_at', Carbon::today())
            ->when(!$isAdmin, fn($q) => $q->where('user_id', $user->id))
            ->sum('total_amount');

        // Best selling product (Filtered for cashier)
        $bestSellingProduct = TransactionDetail::with('product')
            ->whereHas('transaction', function($q) use ($isAdmin, $user) {
                $q->when(!$isAdmin, fn($query) => $query->where('user_id', $user->id));
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->first();

        // Last 7 days data for charts (Filtered for cashier)
        $transactionChartData = $this->getLast7DaysTransactionData($isAdmin, $user->id);
        $revenueChartData = $this->getLast7DaysRevenueData($isAdmin, $user->id);

        if ($isAdmin) {
            // Get previous month data for comparison
            $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

            // Financial summaries (Current Month)
            $income = Transaction::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])->sum('total_amount');
            $expenses = Expense::whereBetween('date', [$currentMonthStart, $currentMonthEnd])->sum('amount');
            $profit = $income - $expenses;

            // Monthly comparisons
            $monthlyComparison = $this->getMonthlyComparison($currentMonthStart, $currentMonthEnd);
            $profitComparison = $this->getProfitComparison($currentMonthStart, $currentMonthEnd, $previousMonthStart, $previousMonthEnd);

            // --- Premium Admin Insights ---
            // 1. Performa Kasir Hari Ini
            $cashierPerformance = Transaction::with('user')
                ->whereDate('created_at', Carbon::today())
                ->select('user_id', DB::raw('COUNT(*) as total_transactions'), DB::raw('SUM(total_amount) as total_revenue'))
                ->groupBy('user_id')
                ->orderByDesc('total_revenue')
                ->get();

            // 2. Penjualan per Jam Hari Ini
            $hourlySales = Transaction::whereDate('created_at', Carbon::today())
                ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('SUM(total_amount) as total'))
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            // 3. Distribusi Kategori (Bulan Ini)
            $categoryDistribution = TransactionDetail::join('products', 'transaction_details.product_id', '=', 'products.id')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
                ->whereMonth('transactions.created_at', now()->month)
                ->whereYear('transactions.created_at', now()->year)
                ->select('categories.name', DB::raw('SUM(transaction_details.subtotal) as total'))
                ->groupBy('categories.id', 'categories.name')
                ->orderByDesc('total')
                ->get();

            // 4. Aktivitas Terbaru
            $recentActivities = \App\Models\ActivityLog::with('user')
                ->latest()
                ->take(5)
                ->get();

            // 5. Retail Insights
            $totalSupplierDebt = \App\Models\PurchaseOrder::where('status', '!=', 'cancelled')
                ->where('payment_status', '!=', 'paid')
                ->get()
                ->sum(fn($po) => $po->total_amount - $po->paid_amount);
                
            $activeOpnamesCount = \App\Models\StockOpname::where('status', 'pending')->count();

            return view('dashboard.index', compact(
                'todayTransactions',
                'todayRevenue',
                'bestSellingProduct',
                'transactionChartData',
                'revenueChartData',
                'income', 
                'expenses', 
                'profit',
                'monthlyComparison',
                'profitComparison',
                'cashierPerformance',
                'hourlySales',
                'categoryDistribution',
                'recentActivities',
                'totalSupplierDebt',
                'activeOpnamesCount'
            ));
        }

        // For Cashier
        $recentTransactions = Transaction::with(['customer', 'paymentMethod'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.cashier', compact(
            'todayTransactions',
            'todayRevenue',
            'bestSellingProduct',
            'transactionChartData',
            'revenueChartData',
            'recentTransactions'
        ));
    }

    private function getLast7DaysTransactionData($isAdmin = true, $userId = null)
    {
        return Transaction::select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->when(!$isAdmin && $userId, fn($q) => $q->where('user_id', $userId))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getLast7DaysRevenueData($isAdmin = true, $userId = null)
    {
        return Transaction::select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('SUM(total_amount) as total')
            )
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->when(!$isAdmin && $userId, fn($q) => $q->where('user_id', $userId))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getMonthlyComparison($startDate, $endDate)
    {
        // Current period transactions
        $currentPeriod = Transaction::selectRaw('
                SUM(total_amount) as total,
                COUNT(*) as transaction_count
            ')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->first();
        
        // Current period expenses
        $currentExpenses = Expense::selectRaw('SUM(amount) as total')
            ->whereBetween('date', [$startDate, $endDate])
            ->first();

        // Previous period dates
        $previousStart = Carbon::parse($startDate)->subMonth();
        $previousEnd = Carbon::parse($endDate)->subMonth();
        
        // Previous period transactions
        $previousPeriod = Transaction::selectRaw('
                SUM(total_amount) as total,
                COUNT(*) as transaction_count
            ')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->first();
        
        // Previous period expenses
        $previousExpenses = Expense::selectRaw('SUM(amount) as total')
            ->whereBetween('date', [$previousStart, $previousEnd])
            ->first();

        return [
            'current' => [
                'expense' => $currentExpenses->total ?? 0,
                'transactions' => $currentPeriod->transaction_count ?? 0,
                'revenue' => $currentPeriod->total ?? 0
            ],
            'previous' => [
                'expense' => $previousExpenses->total ?? 0,
                'transactions' => $previousPeriod->transaction_count ?? 0,
                'revenue' => $previousPeriod->total ?? 0
            ],
            'expense_change' => $this->calculatePercentageChange(
                $previousExpenses->total ?? 0, 
                $currentExpenses->total ?? 0
            ),
            'transaction_change' => $this->calculatePercentageChange(
                $previousPeriod->transaction_count ?? 0, 
                $currentPeriod->transaction_count ?? 0
            ),
            'revenue_change' => $this->calculatePercentageChange(
                $previousPeriod->total ?? 0, 
                $currentPeriod->total ?? 0
            )
        ];
    }

    private function getProfitComparison($currentStart, $currentEnd, $previousStart, $previousEnd)
    {
        // Current period profit
        $currentRevenue = Transaction::whereBetween('created_at', [$currentStart, $currentEnd])
            ->sum('total_amount');
        $currentExpenses = Expense::whereBetween('date', [$currentStart, $currentEnd])
            ->sum('amount');
        $currentProfit = $currentRevenue - $currentExpenses;

        // Previous period profit
        $previousRevenue = Transaction::whereBetween('created_at', [$previousStart, $previousEnd])
            ->sum('total_amount');
        $previousExpenses = Expense::whereBetween('date', [$previousStart, $previousEnd])
            ->sum('amount');
        $previousProfit = $previousRevenue - $previousExpenses;

        return $this->calculatePercentageChange($previousProfit, $currentProfit);
    }

    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue == 0 ? 0 : 100; // Handle division by zero
        }
        return (($newValue - $oldValue) / abs($oldValue)) * 100;
    }
}