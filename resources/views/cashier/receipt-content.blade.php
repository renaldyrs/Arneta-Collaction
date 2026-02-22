<div class="bg-white dark:bg-gray-800 p-6 rounded-lg"
    style="max-width: 400px; margin: 0 auto; font-family: 'Courier New', monospace;">
    <!-- Header -->
    <div class="text-center mb-4 border-b border-gray-300 dark:border-gray-600 pb-3">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">{{ $storeProfile->name ?? 'Toko' }}</h2>
        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $storeProfile->address ?? '-' }}</p>
        <p class="text-xs text-gray-600 dark:text-gray-400">Telp: {{ $storeProfile->phone ?? '-' }}</p>
    </div>

    <!-- Transaction Info -->
    <div class="text-xs space-y-1 mb-4 border-b border-gray-300 dark:border-gray-600 pb-3">
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">No. Invoice:</span>
            <span class="font-medium text-gray-800 dark:text-white">{{ $transaction->invoice_number }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Tanggal:</span>
            <span
                class="font-medium text-gray-800 dark:text-white">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Kasir:</span>
            <span class="font-medium text-gray-800 dark:text-white">{{ $transaction->user->name ?? '-' }}</span>
        </div>
    </div>

    <!-- Items -->
    <div class="mb-4 border-b border-gray-300 dark:border-gray-600 pb-3">
        @foreach($transaction->details as $detail)
            <div class="mb-2">
                <div class="font-medium text-gray-800 dark:text-white">{{ $detail->product->name }}</div>
                @if($detail->size)
                    <div class="text-xs text-gray-500 dark:text-gray-400 ml-2">Ukuran: {{ $detail->size }}</div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">{{ $detail->quantity }} x Rp
                        {{ number_format($detail->price, 0, ',', '.') }}</span>
                    <span class="font-medium text-gray-800 dark:text-white">Rp
                        {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Total -->
    <div class="mb-4 border-b border-gray-300 dark:border-gray-600 pb-3">
        <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
            <span class="font-medium text-gray-800 dark:text-white">Rp
                {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Diskon</span>
            <span class="font-medium text-gray-800 dark:text-white">Rp
                {{ number_format($transaction->discount_amount ?? 0, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-bold text-base mt-2">
            <span class="text-gray-800 dark:text-white">TOTAL</span>
            <span class="text-blue-600 dark:text-blue-400">Rp
                {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="text-xs space-y-1 mb-4 border-b border-gray-300 dark:border-gray-600 pb-3">
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Metode Pembayaran:</span>
            <span
                class="font-medium text-gray-800 dark:text-white">{{ $transaction->paymentMethod->name ?? 'Tunai' }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Jumlah Bayar:</span>
            <span class="font-medium text-gray-800 dark:text-white">Rp
                {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Kembalian:</span>
            <span class="font-medium text-gray-800 dark:text-white">Rp
                {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center text-xs text-gray-500 dark:text-gray-400">
        <p class="font-bold text-gray-700 dark:text-gray-300 tracking-wider">★ TERIMA KASIH ★</p>
        <p class="mt-1">Atas kepercayaan Anda berbelanja di</p>
        <p class="font-semibold text-gray-700 dark:text-gray-300">{{ $storeProfile->name ?? 'Toko Kami' }}</p>
        <div class="border-t border-dashed border-gray-300 dark:border-gray-600 mt-2 pt-2">
            <p>Barang yang sudah dibeli tidak dapat</p>
            <p>ditukar atau dikembalikan</p>
        </div>
        @if(!empty($storeProfile->email))
            <p class="mt-2">📧 {{ $storeProfile->email }}</p>
        @endif
        <div class="border-t border-dashed border-gray-300 dark:border-gray-600 mt-2 pt-2">
            <p style="font-size: 10px;" class="text-gray-400">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
            <p style="font-size: 10px;" class="text-gray-400 font-semibold mt-0.5">Powered by Arneta POS v2.0</p>
        </div>
    </div>

</div>