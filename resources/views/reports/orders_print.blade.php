<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pesanan - {{ $startDate }} s/d {{ $endDate }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 20px;
        }
        .store-info h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0d9373;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: -0.025em;
        }
        .store-info p {
            margin: 2px 0;
            color: #6b7280;
            font-size: 10px;
        }
        .report-title {
            text-align: right;
        }
        .report-title h2 {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            color: #111827;
        }
        .report-title p {
            margin: 4px 0 0;
            color: #6b7280;
            font-weight: 600;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: #f9fafb;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #f3f4f6;
        }
        .stat-label {
            font-size: 9px;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 14px;
            font-weight: 800;
            color: #111827;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background: #f9fafb;
            color: #374151;
            font-weight: 700;
            text-align: left;
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.025em;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }
        .font-mono { font-family: 'Courier New', monospace; font-weight: 600; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .status-completed { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-cancelled { background: #fee2e2; color: #991b1b; }
        
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 150px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .print-info {
            font-size: 8px;
            color: #9ca3af;
            font-style: italic;
        }

        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="background: #f3f4f6; padding: 15px; text-align: center; border-bottom: 1px solid #e5e7eb; margin-bottom: 30px;">
        <button onclick="window.print()" style="background: #0d9373; color: white; border: none; padding: 8px 20px; border-radius: 6px; font-weight: 600; cursor: pointer;">
            <i class="fas fa-print"></i> Cetak Sekarang
        </button>
    </div>

    <div class="header">
        <div class="store-info">
            <h1>{{ $storeProfile->name ?? 'ARNETA COLLECTION' }}</h1>
            <p>{{ $storeProfile->address ?? 'Alamat Toko Belum Diatur' }}</p>
            <p>Telp/WA: {{ $storeProfile->phone ?? '-' }} | Email: {{ $storeProfile->email ?? '-' }}</p>
        </div>
        <div class="report-title">
            <h2>LAPORAN PESANAN</h2>
            <p>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $totalOrders }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Omzet (Net)</div>
            <div class="stat-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pesanan Selesai</div>
            <div class="stat-value">{{ $completedOrders }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">No. Invoice</th>
                <th width="12%">Tanggal</th>
                <th width="18%">Pelanggan</th>
                <th>Detail Items</th>
                <th width="10%" class="text-center">Status</th>
                <th width="15%" class="text-right">Total Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td class="font-mono">#{{ $order->invoice_number }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d/m/Y') }}</td>
                    <td>
                        <div class="font-bold">{{ $order->customer->name ?? 'Umum' }}</div>
                        <div style="font-size: 9px; color: #6b7280;">{{ $order->customer->phone ?? '' }}</div>
                    </td>
                    <td>
                        <div style="font-size: 9px;">
                            @foreach ($order->items->take(3) as $item)
                                {{ $item->product->name }} ({{ $item->quantity }}){{ !$loop->last ? ',' : '' }}
                            @endforeach
                            @if ($order->items->count() > 3)
                                <span style="color: #9ca3af;">+{{ $order->items->count() - 3 }} lainnya</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="status-badge status-{{ $order->status }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="text-right font-bold">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="print-info">
            Dicetak pada: {{ now()->format('d/m/Y H:i') }}<br>
            Oleh: {{ auth()->user()->name }}
        </div>
        <div class="signature-box">
            <p>Admin Toko,</p>
            <div class="signature-line">
                ( {{ auth()->user()->name }} )
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Uncomment to auto-print
            // window.print();
        }
    </script>
</body>
</html>
