<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\Product;

$argv = $_SERVER['argv'] ?? [];
$startArg = $argv[1] ?? null;
$endArg = $argv[2] ?? null;

$startDate = $startArg ?: Carbon::now()->startOfMonth()->format('Y-m-d');
$endDate = $endArg ?: Carbon::now()->endOfMonth()->format('Y-m-d');

echo "Generating financial report for period: {$startDate} to {$endDate}\n";

$transactions = Transaction::with(['details.product', 'paymentMethod', 'customer', 'user'])
    ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
    ->orderBy('created_at', 'desc')
    ->get();

$expenses = Expense::whereBetween('date', [$startDate, $endDate])->get();

// inventory value
$inventoryValue = 0;
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
}

$totalRevenue = $transactions->sum('total_amount');
$totalDiscount = $transactions->sum('discount_amount');
$totalExpenses = $expenses->sum('amount');
$netProfit = $totalRevenue - $totalExpenses;

$dir = __DIR__ . '/../storage/reports';
if (!is_dir($dir)) mkdir($dir, 0755, true);
$filename = $dir . '/laporan_keuangan_' . $startDate . '_' . $endDate . '.csv';

$handle = fopen($filename, 'w');
// BOM
fwrite($handle, "\xEF\xBB\xBF");

fputcsv($handle, ['LAPORAN KEUANGAN']);
fputcsv($handle, ['Periode', $startDate . ' s/d ' . $endDate]);
fputcsv($handle, ['Diekspor pada', Carbon::now()->format('d/m/Y H:i')]);
fputcsv($handle, []);

fputcsv($handle, ['--- RINGKASAN ---']);
fputcsv($handle, ['Total Pendapatan (Gross)', number_format($totalRevenue, 2, '.', '')]);
fputcsv($handle, ['Total Diskon', number_format($totalDiscount, 2, '.', '')]);
fputcsv($handle, ['Total Pengeluaran', number_format($totalExpenses, 2, '.', '')]);
fputcsv($handle, ['Nilai Persediaan (stok x cost)', number_format($inventoryValue, 2, '.', '')]);
fputcsv($handle, ['Laba Bersih', number_format($netProfit, 2, '.', '')]);
fputcsv($handle, []);

fputcsv($handle, ['--- DAFTAR TRANSAKSI ---']);
fputcsv($handle, [
    'No. Invoice','Tanggal','Kasir','Pelanggan','Metode Bayar','Subtotal','Diskon','Total','Uang Bayar','Kembalian'
]);

foreach ($transactions as $trx) {
    fputcsv($handle, [
        $trx->invoice_number,
        $trx->created_at->format('d/m/Y H:i'),
        $trx->user->name ?? '-',
        $trx->customer->name ?? 'Umum',
        $trx->paymentMethod->name ?? '-',
        number_format($trx->total_amount + $trx->discount_amount, 2, '.', ''),
        number_format($trx->discount_amount, 2, '.', ''),
        number_format($trx->total_amount, 2, '.', ''),
        number_format($trx->payment_amount, 2, '.', ''),
        number_format($trx->change_amount, 2, '.', ''),
    ]);
}

fputcsv($handle, []);
fputcsv($handle, ['--- DAFTAR PENGELUARAN ---']);
fputcsv($handle, ['Tanggal','Keterangan','Jumlah']);
foreach ($expenses as $exp) {
    fputcsv($handle, [$exp->date, $exp->description ?? $exp->name ?? '-', number_format($exp->amount, 2, '.', '')]);
}

fclose($handle);

echo "Report saved to: {$filename}\n";
