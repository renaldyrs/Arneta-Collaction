<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Struk - {{ $transaction->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
            background: white;
            color: black;
            width: 80mm; /* Ukuran thermal printer 80mm */
            margin: 0 auto;
        }
        
        .receipt {
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }
        
        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        
        .store-detail {
            font-size: 11px;
            margin: 2px 0;
        }
        
        .transaction-info {
            font-size: 11px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        
        .items {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }
        
        .item {
            margin-bottom: 8px;
        }
        
        .item-name {
            font-size: 12px;
            font-weight: bold;
            margin: 0 0 2px 0;
        }
        
        .item-size {
            font-size: 10px;
            margin-left: 5px;
            color: #555;
        }
        
        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin-left: 5px;
        }
        
        .total-section {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin: 3px 0;
        }
        
        .grand-total {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px solid #000;
        }
        
        .payment-info {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #000;
            font-size: 11px;
        }
        
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 15px;
        }
        
        .thank-you {
            font-size: 12px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        /* Style untuk print */
        @media print {
            body {
                padding: 0;
                width: 100%;
            }
            
            .no-print {
                display: none;
            }
        }
        
        /* Style untuk screen */
        @media screen {
            body {
                background: #f5f5f5;
                padding: 20px;
            }
            
            .receipt {
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1 class="store-name">{{ $storeProfile->name ?? 'TOKO' }}</h1>
            <p class="store-detail">{{ $storeProfile->address ?? '-' }}</p>
            <p class="store-detail">Telp: {{ $storeProfile->phone ?? '-' }}</p>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div class="info-row">
                <span>No. Invoice</span>
                <span>{{ $transaction->invoice_number }}</span>
            </div>
            <div class="info-row">
                <span>Tanggal</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span>Kasir</span>
                <span>{{ $transaction->user->name ?? '-' }}</span>
            </div>
        </div>

        <!-- Items -->
        <div class="items">
            @foreach($transaction->details as $detail)
            <div class="item">
                <div class="item-name">
                    {{ $detail->product->name }}
                    @if($detail->size)
                    <span class="item-size">({{ $detail->size }})</span>
                    @endif
                </div>
                <div class="item-detail">
                    <span>{{ $detail->quantity }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Total -->
        <div class="total-section">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Diskon</span>
                <span>Rp 0</span>
            </div>
            <div class="total-row grand-total">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="info-row">
                <span>Metode Pembayaran</span>
                <span>{{ $transaction->paymentMethod->name ?? 'Tunai' }}</span>
            </div>
            <div class="info-row">
                <span>Jumlah Bayar</span>
                <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
            </div>
            <div class="info-row">
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
        // Auto print saat halaman dimuat
        window.onload = function() {
            console.log('Print page loaded');
            
            // Tunggu sebentar untuk memastikan semua konten termuat
            setTimeout(function() {
                console.log('Triggering print dialog...');
                
                // Coba trigger print
                window.print();
                
                // Optional: Auto close setelah print dialog ditutup
                window.onafterprint = function() {
                    console.log('Print dialog closed');
                    // Bisa redirect atau close window
                    // window.close();
                };
                
                // Fallback untuk browser yang tidak support afterprint
                setTimeout(function() {
                    console.log('Check if print dialog was opened');
                }, 1000);
                
            }, 500);
        };
    </script>
</body>
</html>