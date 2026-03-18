<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - {{ $purchaseOrder->po_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .store-logo h1 {
            color: #0d9373;
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }
        .doc-title {
            text-align: right;
        }
        .doc-title h2 {
            margin: 0;
            font-size: 18px;
            color: #000;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }
        .info-section h3 {
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 8px;
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
        }
        .info-content p {
            margin: 3px 0;
            font-size: 11px;
        }
        .po-meta {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .po-meta table {
            width: 100%;
        }
        .po-meta td {
            padding: 2px 0;
        }
        .po-meta .label {
            font-weight: bold;
            color: #666;
            width: 100px;
        }
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table.items-table th {
            background: #333;
            color: #fff;
            text-align: left;
            padding: 8px;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.items-table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        .totals-section {
            float: right;
            width: 250px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }
        .total-grand {
            border-top: 2px solid #333;
            margin-top: 5px;
            padding-top: 8px;
            font-size: 14px;
            font-weight: bold;
        }
        .notes-section {
            margin-top: 40px;
            clear: both;
        }
        .notes-box {
            border: 1px solid #eee;
            padding: 10px;
            min-height: 60px;
            background: #fff;
            border-radius: 4px;
        }
        .footer {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 180px;
        }
        .signature-name {
            margin-top: 50px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            font-weight: bold;
        }
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; padding: 10px; background: #eee;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Purchase Order</button>
    </div>

    <div class="header">
        <div class="store-logo">
            <h1>{{ $storeProfile->name ?? 'ARNETA COLLECTION' }}</h1>
            <p style="font-size: 10px; margin: 5px 0 0 0;">{{ $storeProfile->address ?? '' }}</p>
            <p style="font-size: 10px; margin: 2px 0 0 0;">Telp: {{ $storeProfile->phone ?? '' }}</p>
        </div>
        <div class="doc-title">
            <h2>PURCHASE ORDER</h2>
            <p style="font-weight: bold; font-size: 14px; margin: 5px 0 0 0;">#{{ $purchaseOrder->po_number }}</p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-section">
            <h3>Kepada Supplier:</h3>
            <div class="info-content">
                <p class="font-bold">{{ $purchaseOrder->supplier->name }}</p>
                <p>{{ $purchaseOrder->supplier->address ?? '-' }}</p>
                <p>Telp: {{ $purchaseOrder->supplier->phone ?? '-' }}</p>
                <p>Email: {{ $purchaseOrder->supplier->email ?? '-' }}</p>
            </div>
        </div>
        <div class="info-section">
            <h3>Informasi Pesanan:</h3>
            <div class="po-meta">
                <table>
                    <tr><td class="label">Tanggal PO</td><td>: {{ $purchaseOrder->created_at->format('d/m/Y') }}</td></tr>
                    <tr><td class="label">Estimasi Tiba</td><td>: {{ $purchaseOrder->expected_date ? $purchaseOrder->expected_date->format('d/m/Y') : '-' }}</td></tr>
                    <tr><td class="label">Status Bayar</td><td>: {{ strtoupper($purchaseOrder->payment_status ?: 'UNPAID') }}</td></tr>
                    <tr><td class="label">Dibuat Oleh</td><td>: {{ $purchaseOrder->user->name }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Deskripsi Produk</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="15%" class="text-right">Harga Satuan</th>
                <th width="20%" class="text-right">Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrder->details as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <div class="font-bold">{{ $detail->product->name }}</div>
                    <div style="font-size: 9px; color: #666;">SKU: {{ $detail->product->code }}</div>
                </td>
                <td class="text-center">{{ $detail->quantity_ordered }}</td>
                <td class="text-right">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-section">
        <div class="total-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="total-row total-grand">
            <span>TOTAL TERBILANG</span>
            <span>Rp {{ number_format($purchaseOrder->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    @if ($purchaseOrder->notes)
    <div class="notes-section">
        <h3 style="font-size: 10px; text-transform: uppercase; color: #666; margin-bottom: 5px;">Catatan:</h3>
        <div class="notes-box">
            {{ $purchaseOrder->notes }}
        </div>
    </div>
    @endif

    <div class="footer">
        <div class="signature-box">
            <p>Dibuat Oleh,</p>
            <div class="signature-name">{{ $purchaseOrder->user->name }}</div>
            <p style="font-size: 9px; color: #666;">( Admin / Purchasing )</p>
        </div>
        <div class="signature-box">
            <p>Supplier,</p>
            <div class="signature-name">&nbsp;</div>
            <p style="font-size: 9px; color: #666;">( Tanda Tangan & Stempel )</p>
        </div>
    </div>

    <div style="margin-top: 50px; font-size: 8px; color: #999; text-align: center;">
        Dokumen ini dibuat secara otomatis melalui sistem Arneta POS pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
