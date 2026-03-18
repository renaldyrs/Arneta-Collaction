<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan {{ $startDate }} - {{ $endDate }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #222 }
        .header { text-align: center; margin-bottom: 12px }
        .summary { margin-bottom: 12px }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px }
        th, td { border: 1px solid #ddd; padding: 6px; font-size: 11px }
        th { background: #f4f4f4 }
        .right { text-align: right }
        .muted { color: #666; font-size: 10px }
        .warn { color: #b45309; font-weight: bold }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $storeProfile->name ?? 'Arneta Collection' }}</h2>
        <div class="muted">LAPORAN KEUANGAN — Periode: {{ $startDate }} s/d {{ $endDate }}</div>
    </div>

    @if (!empty($productsMissingCostCount) && $productsMissingCostCount > 0)
        <div class="warn">Peringatan: Terdapat {{ $productsMissingCostCount }} produk dengan nilai cost kosong/0. Contoh: {{ implode(', ', array_map(fn($p)=> $p['name'], $productsMissingCostSamples)) }}</div>
    @endif

    <div class="summary">
        <table>
            <tr>
                <th>Item</th>
                <th class="right">Nilai</th>
            </tr>
            <tr><td>Pendapatan (Gross)</td><td class="right">Rp {{ number_format($totalRevenue,2,',','.') }}</td></tr>
            <tr><td>Diskon</td><td class="right">Rp {{ number_format($totalDiscount,2,',','.') }}</td></tr>
            <tr><td>Pengeluaran</td><td class="right">Rp {{ number_format($totalExpenses,2,',','.') }}</td></tr>
            <tr><td>Nilai Persediaan (stok x cost)</td><td class="right">Rp {{ number_format($inventoryValue,2,',','.') }}</td></tr>
            <tr><th>Laba Bersih</th><th class="right">Rp {{ number_format($netProfit,2,',','.') }}</th></tr>
        </table>
    </div>

    <h4>Daftar Transaksi</h4>
    <table>
        <thead>
            <tr>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Pelanggan</th>
                <th class="right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($transactions as $trx)
            <tr>
                <td>{{ $trx->invoice_number }}</td>
                <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $trx->user->name ?? '-' }}</td>
                <td>{{ $trx->customer->name ?? 'Umum' }}</td>
                <td class="right">{{ number_format($trx->total_amount,2,',','.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="muted">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>
