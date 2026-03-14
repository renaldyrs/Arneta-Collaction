<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Product;

$argv = $_SERVER['argv'] ?? [];
$startArg = $argv[1] ?? null;
$endArg = $argv[2] ?? null;

$startDate = $startArg ?: Carbon::now()->startOfMonth()->format('Y-m-d');
$endDate = $endArg ?: Carbon::now()->endOfMonth()->format('Y-m-d');

echo "Generating PDF financial report for period: {$startDate} to {$endDate}\n";

$transactions = Transaction::with(['details.product', 'paymentMethod', 'customer', 'user'])
    ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
    ->orderBy('created_at', 'desc')
    ->get();

$expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();

// inventory
$inventoryValue = 0;
$productsMissingCost = collect();
$products = Product::with('sizes')->get();
foreach ($products as $p) {
    $effectiveStock = 0;
    if ($p->relationLoaded('sizes') && $p->sizes->count() > 0) {
        $effectiveStock = $p->sizes->sum('pivot.stock');
    } else {
        $effectiveStock = (int) ($p->stock ?? 0);
    }
    $unitCost = $p->cost && $p->cost > 0 ? (float) $p->cost : (float) ($p->price * 0.6);
    $inventoryValue += $effectiveStock * $unitCost;
    if ((!isset($p->cost) || $p->cost == 0) && $effectiveStock > 0) {
        $productsMissingCost->push(['id' => $p->id, 'name' => $p->name ?: ('#' . $p->id)]);
    }
}
$productsMissingCostCount = $productsMissingCost->count();
$productsMissingCostSamples = $productsMissingCost->take(5)->values()->all();

$totalRevenue = $transactions->sum('total_amount');
$totalDiscount = $transactions->sum('discount_amount');
$totalExpenses = $expenses->sum('amount');
$netProfit = $totalRevenue - $totalExpenses;

$storeProfile = \App\Models\StoreProfile::first();

$pdf = Pdf::loadView('reports.financial_pdf', compact(
    'transactions','totalRevenue','totalDiscount','totalExpenses','netProfit',
    'startDate','endDate','storeProfile','inventoryValue','productsMissingCostCount','productsMissingCostSamples'
))->setPaper('a4', 'landscape');

$dir = __DIR__ . '/../storage/reports';
if (!is_dir($dir)) mkdir($dir, 0755, true);
$filename = $dir . '/laporan_keuangan_' . $startDate . '_' . $endDate . '.pdf';
$pdf->save($filename);

echo "PDF saved to: {$filename}\n";
