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
        // Get current month data
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        
        // Get previous month data for comparison
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Today's data
        $todayTransactions = Transaction::whereDate('created_at', Carbon::today())->count();
        $todayRevenue = Transaction::whereDate('created_at', Carbon::today())->sum('total_amount');

        // Best selling product
        $bestSellingProduct = TransactionDetail::with('product')
            ->select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->first();

        // Last 7 days data for charts
        $transactionChartData = $this->getLast7DaysTransactionData();
        $revenueChartData = $this->getLast7DaysRevenueData();

        // Financial summaries
        $income = Transaction::sum('total_amount');
        $expenses = Expense::sum('amount');
        $profit = $income - $expenses;

        // Monthly comparisons
        $monthlyComparison = $this->getMonthlyComparison($currentMonthStart, $currentMonthEnd);
        $profitComparison = $this->getProfitComparison($currentMonthStart, $currentMonthEnd, $previousMonthStart, $previousMonthEnd);

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
            'profitComparison'
        ));
    }

    private function getLast7DaysTransactionData()
    {
        return Transaction::select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getLast7DaysRevenueData()
    {
        return Transaction::select(
                DB::raw('DATE(created_at) as date'), 
                DB::raw('SUM(total_amount) as total')
            )
            ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
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