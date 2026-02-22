<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProductController,
    SupplierController,
    CategoryController,
    CashierController,
    DashboardController,
    ReportController,
    ProfileController,
    UserController,
    StoreProfileController,
    ExpenseController,
    FinancialReportController,
    ReturnController,
    PaymentMethodController,
    CustomerController,
    DiscountController,
    PurchaseOrderController,
    ShiftController,
    ActivityLogController,
    TransactionHistoryController,
    HomeController,
};


// Root -> Login
Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// =====================================================================
// Dashboard
// =====================================================================
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// =====================================================================
// Master Data (Admin only)
// =====================================================================
Route::middleware(['auth', 'isAdmin'])->group(function () {

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Products
    Route::resource('products', ProductController::class);
    Route::get('/products/print-barcodes/{id}', [ProductController::class, 'printBarcodes'])->name('products.print-barcodes');
    Route::get('/products/{id}/download-barcode', [ProductController::class, 'downloadBarcode'])->name('products.downloadBarcode');
    Route::get('/barcode/{code}', [ProductController::class, 'generateBarcode'])->name('barcode.generate');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Payment Methods
    Route::resource('payment', PaymentMethodController::class);

    // Users
    Route::resource('users', UserController::class);

    // Store Profile
    Route::prefix('store-profile')->group(function () {
        Route::get('/', [StoreProfileController::class, 'index'])->name('store-profile.index');
        Route::get('/edit', [StoreProfileController::class, 'edit'])->name('store-profile.edit');
        Route::post('/store', [StoreProfileController::class, 'store'])->name('store-profile.store');
        Route::get('/create', [StoreProfileController::class, 'create'])->name('store-profile.create');
        Route::put('/update', [StoreProfileController::class, 'update'])->name('store-profile.update');
    });

    // Discounts / Promo
    Route::resource('discounts', DiscountController::class);

    // Purchase Orders
    Route::resource('purchase-orders', PurchaseOrderController::class);
    Route::post('/purchase-orders/{purchaseOrder}/mark-ordered', [PurchaseOrderController::class, 'markOrdered'])->name('purchase-orders.mark-ordered');
    Route::post('/purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    Route::post('/purchase-orders/{purchaseOrder}/cancel', [PurchaseOrderController::class, 'cancel'])->name('purchase-orders.cancel');

    // Activity Log & Low Stock
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/low-stock', [ActivityLogController::class, 'lowStock'])->name('low-stock.index');

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/financial', [ReportController::class, 'financialReport'])->name('reports.financial');
        Route::get('/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export-csv');
    });

    // Financial Reports
    Route::prefix('financial-reports')->group(function () {
        Route::get('/', [FinancialReportController::class, 'index'])->name('financial-reports.index');
        Route::get('/export', [FinancialReportController::class, 'exportPdf'])->name('financial-reports.export');
        Route::get('/export-excel', [FinancialReportController::class, 'exportExcel'])->name('financial-reports.export-excel');
    });
});

// =====================================================================
// Transaksi (Admin + Kasir)
// =====================================================================
Route::middleware('auth')->group(function () {

    // Cashier
    Route::prefix('cashier')->group(function () {
        Route::get('/', [CashierController::class, 'index'])->name('cashier.index');
        Route::post('/', [CashierController::class, 'store'])->name('cashier.store');
        Route::get('/invoice/{id}', [CashierController::class, 'invoice'])->name('cashier.invoice');
        Route::get('/invoice/{id}/print', [CashierController::class, 'printInvoice'])->name('cashier.invoice.print');
        Route::get('/orders', [CashierController::class, 'orders'])->name('cashier.orders');
        Route::get('/receipt/{id}', [CashierController::class, 'showReceipt'])->name('cashier.receipt');
        Route::get('/print/{id}', [CashierController::class, 'printReceipt'])->name('cashier.print');
        Route::post('/add-to-cart', [CashierController::class, 'addToCart'])->name('cashier.addToCart');
    });

    // Transaction History
    Route::get('/transactions', [TransactionHistoryController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/export-csv', [TransactionHistoryController::class, 'exportCsv'])->name('transactions.export-csv');

    // Expenses
    Route::resource('expenses', ExpenseController::class);

    // Returns
    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index'])->name('returns.index');
        Route::get('/create/{transaction}', [ReturnController::class, 'create'])->name('returns.create');
        Route::post('/', [ReturnController::class, 'store'])->name('returns.store');
        Route::post('/{id}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
        Route::post('/{id}/reject', [ReturnController::class, 'reject'])->name('returns.reject');
    });

    // Customers
    Route::resource('customers', CustomerController::class);

    // Shifts
    Route::get('/shifts', [ShiftController::class, 'index'])->name('shifts.index');
    Route::post('/shifts/open', [ShiftController::class, 'open'])->name('shifts.open');
    Route::get('/shifts/{shift}', [ShiftController::class, 'show'])->name('shifts.show');
    Route::post('/shifts/{shift}/close', [ShiftController::class, 'close'])->name('shifts.close');

    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    });
});

// =====================================================================
// API Endpoints (untuk AJAX/Mobile)
// =====================================================================
Route::middleware('auth')->prefix('api/v1')->group(function () {
    Route::get('/products/find-by-code', [ProductController::class, 'findByCode'])->name('products.find-by-code');
    Route::get('/products/find-by-barcode/{barcode}', [ProductController::class, 'findByBarcode'])->name('products.findByBarcode');
    Route::get('/customers/search', [CustomerController::class, 'search'])->name('customers.search');
    Route::post('/discounts/validate', [DiscountController::class, 'validate_code'])->name('discounts.validate');

    // Transaction search (untuk modal retur)
    Route::get('/transactions/search', function (\Illuminate\Http\Request $request) {
        $q = $request->input('q');
        $transaction = \App\Models\Transaction::with(['details.product', 'customer', 'paymentMethod'])
            ->where('invoice_number', 'like', "%{$q}%")
            ->latest()
            ->first();
        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Invoice tidak ditemukan']);
        }
        return response()->json(['success' => true, 'transaction' => $transaction]);
    })->name('api.transactions.search');

    Route::get('/transactions/{id}', function ($id) {
        $transaction = \App\Models\Transaction::with(['details.product', 'customer', 'paymentMethod'])
            ->findOrFail($id);
        return response()->json(['success' => true, 'transaction' => $transaction]);
    })->name('api.transactions.show');

    Route::get('/products/low-stock', function () {
        $products = \App\Models\Product::whereColumn('stock', '<=', 'low_stock_threshold')->with('category')->get();
        return response()->json($products);
    })->name('api.low-stock');
    Route::get('/dashboard/stats', function () {
        $isAdmin = auth()->user()?->role === 'admin';
        return response()->json([
            'today_revenue' => \App\Models\Transaction::whereDate('created_at', today())->sum('total_amount'),
            'today_transactions' => \App\Models\Transaction::whereDate('created_at', today())
                ->when(!$isAdmin, fn($q) => $q->where('user_id', auth()->id()))
                ->count(),
            'low_stock_count' => \App\Models\Product::whereColumn('stock', '<=', 'low_stock_threshold')->where('stock', '>=', 0)->count(),
            'out_of_stock_count' => \App\Models\Product::where('stock', '<=', 0)->count(),
            'month_revenue' => \App\Models\Transaction::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_amount'),
            'total_customers' => \App\Models\Customer::count(),
            'sidebar_tx_count' => \App\Models\Transaction::whereDate('created_at', today())
                ->when(!$isAdmin, fn($q) => $q->where('user_id', auth()->id()))
                ->count(),
            'timestamp' => now()->toISOString(),
        ]);
    })->name('api.dashboard-stats');
});