<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
            background: #fff;
            color: #000;
        }
        .receipt {
            max-width: 300px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        .store-detail {
            font-size: 12px;
            margin: 2px 0;
        }
        .transaction-info {
            font-size: 12px;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .items {
            margin-bottom: 15px;
        }
        .item {
            font-size: 12px;
            margin-bottom: 5px;
        }
        .item-name {
            font-weight: bold;
        }
        .item-detail {
            display: flex;
            justify-content: space-between;
            margin-left: 10px;
        }
        .total {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
            margin: 10px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: bold;
        }
        .payment-info {
            font-size: 12px;
            margin-bottom: 15px;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 20px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .thank-you {
            font-weight: bold;
            margin: 5px 0;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1 class="store-name">{{ $storeProfile->name ?? 'Toko' }}</h1>
            <p class="store-detail">{{ $storeProfile->address ?? '-' }}</p>
            <p class="store-detail">Telp: {{ $storeProfile->phone ?? '-' }}</p>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div class="payment-row">
                <span>No. Invoice</span>
                <span>{{ $transaction->invoice_number }}</span>
            </div>
            <div class="payment-row">
                <span>Tanggal</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="payment-row">
                <span>Kasir</span>
                <span>{{ $transaction->user->name ?? '-' }}</span>
            </div>
        </div>

        <!-- Items -->
        <div class="items">
            @foreach($transaction->details as $detail)
            <div class="item">
                <div class="item-name">{{ $detail->product->name }}</div>
                @if($detail->size)
                <div style="margin-left: 10px; font-size: 11px;">Ukuran: {{ $detail->size }}</div>
                @endif
                <div class="item-detail">
                    <span>{{ $detail->quantity }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Total -->
        <div class="total">
            <div class="payment-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="payment-row">
                <span>Diskon</span>
                <span>Rp 0</span>
            </div>
            <div class="total-row">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="payment-row">
                <span>Metode Pembayaran</span>
                <span>{{ $transaction->paymentMethod->name ?? 'Tunai' }}</span>
            </div>
            <div class="payment-row">
                <span>Jumlah Bayar</span>
                <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
            </div>
            <div class="payment-row">
                <span>Kembalian</span>
                <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="thank-you">Terima Kasih</p>
            <p>Barang yang sudah dibeli tidak dapat</p>
            <p>ditukar atau dikembalikan</p>
            <p>www.tokoanda.com</p>
        </div>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>