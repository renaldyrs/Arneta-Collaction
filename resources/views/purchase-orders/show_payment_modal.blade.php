    <!-- Modal Catat Pembayaran -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-md">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Catat Pembayaran</h3>
                <button onclick="document.getElementById('paymentModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            <form action="{{ route('purchase-payments.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                <input type="hidden" name="purchase_order_id" value="{{ $purchaseOrder->id }}">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Jumlah Bayar</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="amount" required min="0" max="{{ $purchaseOrder->total_amount - $purchaseOrder->paid_amount }}" 
                               value="{{ $purchaseOrder->total_amount - $purchaseOrder->paid_amount }}" 
                               class="form-input pl-10" placeholder="0">
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Sisa tagihan: Rp {{ number_format($purchaseOrder->total_amount - $purchaseOrder->paid_amount) }}</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Tanggal Pembayaran</label>
                    <input type="date" name="payment_date" required value="{{ date('Y-m-d') }}" class="form-input">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Metode Pembayaran</label>
                    <select name="payment_method_id" required class="form-select">
                        <option value="">— Pilih Metode —</option>
                        @foreach (\App\Models\PaymentMethod::all() as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-1.5">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" class="form-input" placeholder="Contoh: DP 50% / Pelunasan"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary flex-1">Simpan Pembayaran</button>
                    <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')" class="btn-secondary">Batal</button>
                </div>
            </form>
        </div>
    </div>
