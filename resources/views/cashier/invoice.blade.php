<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->invoice_number }}</title>
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Print Styles */
        @media print {
            body { 
                background-color: white !important; 
                -webkit-print-color-adjust: exact;
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .invoice-container {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            /* Menyesuaikan margin halaman cetak agar tulisan atas tidak kena tepian */
            @page { margin: 15mm; size: auto; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased min-h-screen py-8">

    <div class="container mx-auto px-4">
        
        <!-- Action Buttons (Not Printed) -->
        <div class="no-print flex justify-end gap-3 mb-6 max-w-4xl mx-auto">
            <a href="{{ route('reports.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-md shadow-sm text-sm font-medium transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-md shadow-sm text-sm font-medium transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Invoice
            </button>
        </div>

        <!-- Invoice Container -->
        <div class="invoice-container bg-white p-8 md:p-12 rounded-xl border border-gray-100 shadow-lg max-w-4xl mx-auto">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row justify-between items-start border-b border-gray-200 pb-8 mb-8">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 uppercase">INVOICE</h1>
                    <p class="text-gray-500 mt-1">Nomor: <span class="font-semibold text-gray-800">#{{ $transaction->invoice_number }}</span></p>
                    <p class="text-gray-500">Tanggal: {{ $transaction->created_at->format('d F Y H:i') }}</p>
                    <div class="mt-2 text-sm inline-flex items-center px-2.5 py-0.5 rounded-full font-medium {{ $transaction->total_amount > 0 && $transaction->payment_amount >= $transaction->total_amount ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $transaction->total_amount > 0 && $transaction->payment_amount >= $transaction->total_amount ? 'LUNAS' : 'PENDING' }}
                    </div>
                </div>

                <div class="mt-6 md:mt-0 text-left md:text-right">
                    <h2 class="text-xl font-bold text-gray-900">{{ $storeProfile->name }}</h2>
                    <p class="text-sm text-gray-600 mt-1 max-w-xs md:ml-auto">
                        {{ $storeProfile->address }}
                    </p>
                    <p class="text-sm text-gray-600">Telp: {{ $storeProfile->phone }}</p>
                </div>
            </div>

            <!-- Billing Info Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ditagihkan Kepada</h3>
                    <div class="text-gray-800">
                        <p class="font-semibold text-lg">{{ $transaction->customer ? $transaction->customer->name : 'Pelanggan Umum' }}</p>
                        @if ($transaction->customer && $transaction->customer->phone)
                            <p class="text-sm text-gray-600">{{ $transaction->customer->phone }}</p>
                        @endif
                        @if ($transaction->customer && $transaction->customer->email)
                            <p class="text-sm text-gray-600">{{ $transaction->customer->email }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Informasi Pembayaran</h3>
                    <div class="text-sm text-gray-800 space-y-1">
                        <div class="flex justify-between md:justify-end gap-4">
                            <span class="text-gray-500">Metode Bayar:</span>
                            <span class="font-medium text-right">{{ $transaction->paymentMethod ? $transaction->paymentMethod->name : '-' }}</span>
                        </div>
                        <div class="flex justify-between md:justify-end gap-4">
                            <span class="text-gray-500">Kasir:</span>
                            <span class="font-medium text-right">{{ $transaction->user ? $transaction->user->name : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items Table -->
            <div class="overflow-x-auto mb-8">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider border-y border-gray-200">
                            <th class="py-3 px-4 font-semibold w-12 text-center">No</th>
                            <th class="py-3 px-4 font-semibold">Deskripsi Produk</th>
                            <th class="py-3 px-4 font-semibold text-right w-32">Harga (Rp)</th>
                            <th class="py-3 px-4 font-semibold text-center w-20">Qty</th>
                            <th class="py-3 px-4 font-semibold text-right w-36">Subtotal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($transaction->details as $index => $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-4 text-center text-gray-500">{{ $index + 1 }}</td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-gray-900">{{ $item->product->name ?? '-' }}</p>
                                @if ($item->size)
                                    <p class="text-xs text-gray-500 mt-0.5">Varian: {{ $item->size }}</p>
                                @endif
                                @if ($item->product && $item->product->code)
                                    <p class="text-[10px] text-gray-400 mt-0.5 uppercase tracking-wide">SKU: {{ $item->product->code }}</p>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right text-gray-700">{{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-4 px-4 text-center text-gray-700">{{ collect([$item->quantity])->first() }}</td>
                            <td class="py-4 px-4 text-right font-medium text-gray-800">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Calculations -->
            <div class="flex flex-col md:flex-row justify-between items-end border-t border-gray-200 pt-6">
                <!-- Notes -->
                <div class="w-full md:w-1/2 mb-6 md:mb-0">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan Tambahan</h4>
                    <p class="text-sm text-gray-600 italic border-l-2 border-gray-200 pl-3">
                        {{ $transaction->notes ? $transaction->notes : 'Tidak ada catatan.' }}
                    </p>
                </div>

                <!-- Totals -->
                <div class="w-full md:w-80 space-y-3">
                    @php
                        $rawSubtotal = $transaction->total_amount + $transaction->discount_amount;
                    @endphp
                    
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal Item</span>
                        <span class="text-gray-800 font-medium">Rp {{ number_format($rawSubtotal, 0, ',', '.') }}</span>
                    </div>

                    @if ($transaction->discount_amount > 0)
                    <div class="flex justify-between text-sm text-green-600 bg-green-50 p-1.5 rounded-md px-2 -mx-2">
                        <span>Diskon @if ($transaction->discount)({{ $transaction->discount->code }})@endif</span>
                        <span class="font-medium">- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center border-t border-gray-200 pt-3">
                        <span class="text-base font-bold text-gray-900">Total Akhir</span>
                        <span class="text-xl font-bold text-blue-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>

                    <div class="border-t border-dashed border-gray-300 pt-3 mt-3 space-y-1">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Uang Diterima</span>
                            <span class="text-gray-800 font-medium">Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Kembalian</span>
                            <span class="text-gray-800 font-medium {{ $transaction->change_amount > 0 ? 'text-gray-800' : '' }}">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="mt-12 pt-8 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm font-medium">Terima kasih atas kunjungan dan kepercayaan Anda!</p>
                <p class="text-xs text-gray-400 mt-1">Struk ini adalah bukti pembayaran yang sah. Barang yang sudah dibeli hanya dapat ditukar/retur sesuai dengan kebijakan {{ $storeProfile->name }}.</p>
            </div>

        </div>
    </div> <!-- end container -->

    <!-- Auto-Print Script -->
    @if (session('print'))
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 800); 
        };
    </script>
    @endif
</body>
</html>