<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']);
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $action = $request->input('action');
        $userId = $request->input('user_id');
        $date = $request->input('date');

        $query = ActivityLog::with('user');

        if ($search) {
            $query->where('description', 'like', "%{$search}%");
        }
        if ($action) {
            $query->where('action', $action);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }
        if ($date) {
            $query->whereDate('created_at', $date);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();

        $actions = ActivityLog::distinct()->pluck('action');
        $users = \App\Models\User::orderBy('name')->get();

        return view('activity-logs.index', compact('logs', 'actions', 'users', 'search', 'action', 'userId', 'date'));
    }

    // Daftar produk stok menipis
    public function lowStock()
    {
        $lowStockProducts = Product::whereColumn('stock', '<=', 'low_stock_threshold')
            ->where('stock', '>', 0)
            ->with('category', 'supplier')
            ->orderBy('stock')
            ->get();

        $outOfStockProducts = Product::where('stock', '<=', 0)
            ->with('category', 'supplier')
            ->orderBy('name')
            ->get();

        return view('activity-logs.low-stock', compact('lowStockProducts', 'outOfStockProducts'));
    }
}
