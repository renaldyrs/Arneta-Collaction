<div class="bg-white dark:bg-gray-800 p-6 rounded-lg text-black dark:text-white transition-all"
    style="max-width: {{ ($printSetting->paper_width ?? 80) == 58 ? '300px' : '400px' }}; margin: 0 auto; font-family: 'Courier New', monospace; font-size: {{ ($printSetting->font_size ?? 12) }}px;">
    @php
        $printSetting = \App\Models\PrintSetting::first() ?? (object)[
            'show_logo' => true, 
            'receipt_header' => 'Terima kasih atas kunjungan Anda!', 
            'receipt_footer' => 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.',
            'show_cashier_name' => true,
            'show_customer_name' => true
        ];
    @endphp
    <!-- Header -->
    <div class="text-center mb-4 border-b border-gray-300 dark:border-gray-600 pb-3">
        @if ($printSetting->show_logo && $storeProfile && $storeProfile->logo)
            <div class="flex justify-center mb-2">
                <img src="{{ $storeProfile->logo_url }}" class="h-10 w-auto object-contain filter grayscale">
            </div>
        @endif
        <h2 class="text-xl font-bold text-gray-800 dark:text-white uppercase">{{ $storeProfile->name ?? 'Toko' }}</h2>
        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $storeProfile->address ?? '-' }}</p>
        <p class="text-xs text-gray-600 dark:text-gray-400">Telp: {{ $storeProfile->phone ?? '-' }}</p>
        @if ($printSetting->receipt_header)
            <p class="text-[10px] italic mt-2 text-gray-500">{{ $printSetting->receipt_header }}</p>
        @endif
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
        @if ($printSetting->show_cashier_name)
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Kasir:</span>
            <span class="font-medium text-gray-800 dark:text-white">{{ $transaction->user->name ?? '-' }}</span>
        </div>
        @endif
        @if ($printSetting->show_customer_name && $transaction->customer)
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-400">Pelanggan:</span>
            <span class="font-medium text-gray-800 dark:text-white">{{ $transaction->customer->name }}</span>
        </div>
        @endif
    </div>

    <!-- Items -->
    <div class="mb-4 border-b border-gray-300 dark:border-gray-600 pb-3">
        @foreach ($transaction->details as $detail)
            <div class="mb-2">
                <div class="font-medium text-gray-800 dark:text-white">{{ $detail->product->name }}</div>
                @if ($detail->size)
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
        @php $receiptSubtotal = $transaction->details->sum('subtotal'); @endphp
        <div class="flex justify-between text-sm">
            <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
            <span class="font-medium text-gray-800 dark:text-white">Rp
                {{ number_format($receiptSubtotal, 0, ',', '.') }}</span>
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
        @if ($printSetting->show_thank_you_note)
            <p class="font-bold text-gray-700 dark:text-gray-300 tracking-wider">TERIMA KASIH</p>
        @endif
        @if ($printSetting->receipt_footer)
            <div class="mb-3 whitespace-pre-wrap">{!! nl2br(e($printSetting->receipt_footer)) !!}</div>
        @else
            <p>Barang yang sudah dibeli tidak dapat</p>
            <p>ditukar atau dikembalikan</p>
        @endif
        <div class="border-t border-dashed border-gray-300 dark:border-gray-600 mt-2 pt-2">
           
            <p style="font-size: 10px;" class="text-gray-400 font-semibold mt-0.5">Powered by Arneta POS v2.0</p>
        </div>
    </div>

</div>            <span class="font-black text-emerald-400 tabular-nums italic">{{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>
        <div class="pt-2 mt-2 border-t border-white/5 flex justify-between items-center text-[8px]">
            <span class="font-black text-gray-600 uppercase tracking-widest">Protocol</span>
            <span class="font-black text-gray-400 uppercase italic">{{ $transaction->paymentMethod->name ?? 'Standard' }}</span>
        </div>
    </div>

    <!-- Footer -->
                <p class="text-[8px] font-bold text-gray-600 uppercase tracking-widest mt-2">Verified Digital Transaction Card</p>
            @endif
        </div>
        <div class="border-t border-white/5 pt-4">
            <p class="text-[9px] font-black text-emerald-500/40 italic tracking-tighter">Powered by ARNETA CORE v2.5</p>
        </div>
    </div>

</div>
