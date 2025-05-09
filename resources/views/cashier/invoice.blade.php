<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $transaction->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .invoice-container, .invoice-container * {
                visibility: visible;
            }
            .invoice-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <!-- Invoice Content -->
        <div class="invoice-container bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">INVOICE</h1>
                <div class="text-right">
                    <p class="font-semibold">#{{ $transaction->invoice_number }}</p>
                    <p class="text-sm">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            
            <!-- Customer Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded">
                <h2 class="font-bold mb-2">{{ $storeProfile->name }}</h2>
                <p> {{ $storeProfile->address }}</p>
                <p>Telp: {{ $storeProfile->phone }}</p>

            </div>

            <!-- Order Items -->
            <table class="w-full mb-6">
                <thead>
                    <tr class="border-b">
                        <th class="text-left pb-2">No.</th>
                        <th class="text-left pb-2">Produk</th>
                        <th class="text-right pb-2">Harga</th>
                        <th class="text-right pb-2">Qty</th>
                        <th class="text-right pb-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->details as $item)
                    <tr class="border-b">
                        <td class="py-2">{{ $loop->iteration }}</td>
                        <td class="py-2">
                            {{ $item->product->name }}
                            @if($item->size)
                                <span class="text-sm text-gray-500">(Ukuran: {{ $item->size }})</span>
                            @endif
                        </td>
                        <td class="text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold">
                        <td colspan="3" class="text-right pt-2">TOTAL</td>
                        
                        <td class="text-right pt-2">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="text-center text-sm mt-8">
                <p>Terima kasih telah berbelanja!</p>
                <p>{{ $storeProfile->name }}</p>
            </div>
        </div>

        <!-- Action Buttons (Not Printed) -->
        <div class="no-print flex justify-center gap-4 mt-6">
            <button onclick="window.print()" 
                    class="bg-blue-500 text-white px-4 py-2 rounded">
                🖨️ Cetak Ulang
            </button>
            <a href="{{ route('reports.index') }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Auto-Print Script -->
    @if(session('print'))
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500); // Delay 0.5 detik untuk memastikan halaman siap
        };
    </script>
    @endif
</body>
</html>