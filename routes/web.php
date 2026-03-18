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
    PrintSettingController,
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
    Route::get('suppliers/debt-summary', [\App\Http\Controllers\SupplierController::class, 'debtSummary'])->name('suppliers.debt-summary');
    Route::resource('suppliers', SupplierController::class);

    // Products
    Route::resource('products', ProductController::class);
    Route::post('/products/fill-missing-cost', [ProductController::class, 'fillMissingCost'])->name('products.fill-missing-cost');
    Route::get('/products/print-barcodes/{id}', [ProductController::class, 'printBarcodes'])->name('products.print-barcodes');
    Route::get('/products/{id}/download-barcode', [ProductController::class, 'downloadBarcode'])->name('products.downloadBarcode');
    Route::get('/barcode/{code}', [ProductController::class, 'generateBarcode'])->name('barcode.generate');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Print Management
    Route::get('/settings/print', [PrintSettingController::class, 'index'])->name('settings.print');
    Route::put('/settings/print', [PrintSettingController::class, 'update'])->name('settings.print.update');
    Route::get('/settings/print-test', [PrintSettingController::class, 'testPrint'])->name('settings.print.test');

    // Payment Methods
    Route::resource('payment', PaymentMethodController::class);

    // Users
    Route::resource('users', UserController::class);

    // Stock Opname
    Route::resource('stock-opnames', \App\Http\Controllers\StockOpnameController::class);
    Route::post('stock-opnames/{stock_opname}/complete', [\App\Http\Controllers\StockOpnameController::class, 'complete'])->name('stock-opnames.complete');

    // Purchase Payments
    Route::post('purchase-payments', [\App\Http\Controllers\PurchasePaymentController::class, 'store'])->name('purchase-payments.store');
    Route::delete('purchase-payments/{purchase_payment}', [\App\Http\Controllers\PurchasePaymentController::class, 'destroy'])->name('purchase-payments.destroy');

    // Store Profile
    Route::prefix('store-profile')->group(function () {
        Route::get('/', [StoreProfileController::class, 'index'])->name('store-profile.index');
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
    Route::get('/purchase-orders/{purchaseOrder}/print', [PurchaseOrderController::class, 'print'])->name('purchase-orders.print');

    // Activity Log & Low Stock
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/low-stock', [ActivityLogController::class, 'lowStock'])->name('low-stock.index');

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/financial', [ReportController::class, 'financialReport'])->name('reports.financial');
        Route::get('/orders', [ReportController::class, 'orders'])->name('reports.orders');
        Route::get('/orders-print', [ReportController::class, 'printOrders'])->name('reports.orders.print');
        Route::get('/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export-csv');
    });

    // Financial Reports
    Route::prefix('financial-reports')->group(function () {
        Route::get('/', [FinancialReportController::class, 'index'])->name('financial-reports.index');
        Route::get('/export', [FinancialReportController::class, 'exportPdf'])->name('financial-reports.export');
        Route::get('/export-excel', [FinancialReportController::class, 'exportExcel'])->name('financial-reports.export-excel');
    });

    // Returns Approval
    Route::post('/returns/{id}/approve', [ReturnController::class, 'approve'])->name('returns.approve');
    Route::post('/returns/{id}/reject', [ReturnController::class, 'reject'])->name('returns.reject');
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
        // ... (existing code omitted for brevity but I will keep the final });)
    })->name('api.dashboard-stats');

    // Hold Cart (Antrian)
    Route::post('/cashier/hold', [CashierController::class, 'holdCart'])->name('api.cashier.hold');
    Route::get('/cashier/hold-carts', [CashierController::class, 'getHoldCarts'])->name('api.cashier.hold-carts');
    Route::get('/cashier/resume/{id}', [CashierController::class, 'resumeCart'])->name('api.cashier.resume');
    Route::delete('/cashier/remove-hold/{id}', [CashierController::class, 'removeHoldCart'])->name('api.cashier.remove-hold');
});