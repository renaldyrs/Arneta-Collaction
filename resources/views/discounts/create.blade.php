@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('discounts.index') }}" class="text-gray-400 hover:text-gray-600 transition"><i class="fas fa-arrow-left"></i></a>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ isset($discount) ? 'Edit Diskon' : 'Buat Diskon Baru' }}</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <form action="{{ isset($discount) ? route('discounts.update', $discount) : route('discounts.store') }}" method="POST" class="space-y-5">
            @csrf
            @if(isset($discount)) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Diskon <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $discount->name ?? '') }}" required
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kode Promo <span class="text-gray-400">(opsional)</span></label>
                    <input type="text" name="code" value="{{ old('code', $discount->code ?? '') }}" placeholder="Contoh: HEMAT20"
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm uppercase"
                        style="text-transform: uppercase;">
                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika diskon otomatis (tanpa kode)</p>
                    @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipe Diskon <span class="text-red-500">*</span></label>
                    <select name="type" id="discountType" onchange="updateValueLabel()"
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                        <option value="percentage" {{ old('type', $discount->type ?? 'percentage') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed" {{ old('type', $discount->type ?? '') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nilai Diskon <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span id="valuePrefix" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">%</span>
                        <input type="number" name="value" value="{{ old('value', $discount->value ?? '') }}" required step="0.01" min="0.01"
                            class="w-full pl-8 pr-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                    </div>
                    @error('value')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Minimum Pembelian</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">Rp</span>
                        <input type="number" name="min_purchase" value="{{ old('min_purchase', $discount->min_purchase ?? 0) }}" min="0" step="1000"
                            class="w-full pl-10 pr-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maks. Penggunaan <span class="text-gray-400">(kosong = tak terbatas)</span></label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', $discount->max_uses ?? '') }}" min="1"
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ old('start_date', isset($discount->start_date) ? $discount->start_date->format('Y-m-d') : '') }}"
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tanggal Berakhir</label>
                    <input type="date" name="end_date" value="{{ old('end_date', isset($discount->end_date) ? $discount->end_date->format('Y-m-d') : '') }}"
                        class="w-full px-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-500 text-sm">
                    @error('end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                        {{ old('is_active', $discount->is_active ?? true) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                    <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">Aktifkan Diskon</span>
                </label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2.5 rounded-lg text-sm font-medium transition">
                    <i class="fas fa-save mr-2"></i>{{ isset($discount) ? 'Simpan Perubahan' : 'Buat Diskon' }}
                </button>
                <a href="{{ route('discounts.index') }}" class="px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-lg text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateValueLabel() {
    const type = document.getElementById('discountType').value;
    const prefix = document.getElementById('valuePrefix');
    prefix.textContent = type === 'percentage' ? '%' : 'Rp';
}
// Set initial
updateValueLabel();
</script>
@endpush
