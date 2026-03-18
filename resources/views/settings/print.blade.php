@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Manajemen Printer</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 font-medium text-sm">Sesuaikan perangkat keras printer thermal dan tampilan struk Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('settings.print.test') }}" target="_blank" class="px-4 py-2 bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700 text-gray-700 dark:text-gray-300 rounded-xl text-sm font-bold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all flex items-center shadow-sm">
                <i class="fas fa-print mr-2 text-blue-500"></i> Cetak Test
            </a>
            <button form="printSettingForm" type="submit" class="btn-primary" id="saveButton">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </div>

    @if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 flex items-center shadow-sm">
        <i class="fas fa-check-circle mr-3"></i>
        <span class="font-medium">{{ session('success') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {{-- Settings Form --}}
        <div class="lg:col-span-12 xl:col-span-7">
            <form action="{{ route('settings.print.update') }}" method="POST" id="printSettingForm" class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Connection Section --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                        <h3 class="font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-plug mr-3 text-blue-500"></i> Koneksi & Perangkat Keras
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Metode Koneksi</label>
                                <select name="connection_type" id="connection_type" class="form-input focus:ring-blue-500/20">
                                    <option value="browser" {{ $setting->connection_type == 'browser' ? 'selected' : '' }}>Browser (Standar)</option>
                                    <option value="qz" {{ $setting->connection_type == 'qz' ? 'selected' : '' }}>QZ Tray (Pro/Direct)</option>
                                </select>
                            </div>
                            <div id="qz_settings" class="{{ $setting->connection_type == 'qz' ? '' : 'hidden' }}">
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Nama Printer (QZ Tray)</label>
                                <input type="text" name="qz_printer_name" value="{{ $setting->qz_printer_name }}" class="form-input focus:ring-blue-500/20" placeholder="Contoh: EPSON TM-T20">
                            </div>
                        </div>

                        <div id="browser_instructions" class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl {{ $setting->connection_type == 'browser' ? '' : 'hidden' }}">
                            <h4 class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase mb-2">Silent Printing (Cetak Langsung Tanpa Popup)</h4>
                            <p class="text-[11px] text-blue-600 dark:text-blue-300 leading-relaxed">
                                Untuk cetak otomatis tanpa konfirmasi browser, tambahkan flag <code>--kiosk-printing</code> pada shortcut Chrome Anda.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-gray-100 dark:border-gray-700 pt-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Ukuran Kertas</label>
                                <select name="paper_width" class="form-input focus:ring-blue-500/20">
                                    <option value="80" {{ $setting->paper_width == 80 ? 'selected' : '' }}>80mm (Standar)</option>
                                    <option value="58" {{ $setting->paper_width == 58 ? 'selected' : '' }}>58mm (Kecil)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Ukuran Font (Pixel)</label>
                                <input type="number" name="font_size" value="{{ $setting->font_size }}" min="8" max="24" class="form-input focus:ring-blue-500/20">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">
                            <label class="flex items-center p-3 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                <input type="checkbox" name="auto_print_receipt" class="w-4 h-4 text-blue-500 rounded border-gray-300 focus:ring-blue-500" {{ $setting->auto_print_receipt ? 'checked' : '' }} value="1">
                                <span class="ml-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Cetak Otomatis</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                <input type="checkbox" name="auto_cut" class="w-4 h-4 text-blue-500 rounded border-gray-300 focus:ring-blue-500" {{ $setting->auto_cut ? 'checked' : '' }} value="1">
                                <span class="ml-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Potong Kertas</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Content Section --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                        <h3 class="font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-receipt mr-3 text-emerald-500"></i> Tampilan Konten Struk
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-3 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                <input type="checkbox" name="show_logo" class="w-4 h-4 text-emerald-500 rounded border-gray-300 focus:ring-emerald-500" {{ $setting->show_logo ? 'checked' : '' }} value="1">
                                <span class="ml-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Tampilkan Logo Toko</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                <input type="checkbox" name="show_cashier_name" class="w-4 h-4 text-emerald-500 rounded border-gray-300 focus:ring-emerald-500" {{ $setting->show_cashier_name ? 'checked' : '' }} value="1">
                                <span class="ml-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Tampilkan Nama Kasir</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                <input type="checkbox" name="show_customer_name" class="w-4 h-4 text-emerald-500 rounded border-gray-300 focus:ring-emerald-500" {{ $setting->show_customer_name ? 'checked' : '' }} value="1">
                                <span class="ml-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Tampilkan Nama Pelanggan</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                <input type="checkbox" name="show_thank_you_note" class="w-4 h-4 text-emerald-500 rounded border-gray-300 focus:ring-emerald-500" {{ $setting->show_thank_you_note ? 'checked' : '' }} value="1">
                                <span class="ml-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Pesan Terima Kasih</span>
                            </label>
                        </div>

                        <div class="space-y-4 pt-2">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Teks Ucapan Header</label>
                                <input type="text" name="receipt_header" value="{{ $setting->receipt_header }}" class="form-input" placeholder="Contoh: Selamat Datang di Arneta">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Pesan Penutup (Footer)</label>
                                <textarea name="receipt_footer" rows="2" class="form-input resize-none" placeholder="Contoh: Barang tidak dapat ditukar">{{ $setting->receipt_footer }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Barcode Section --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/30">
                        <h3 class="font-bold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-barcode mr-3 text-indigo-500"></i> Pengaturan Barcode
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Lebar (mm)</label>
                                        <input type="number" name="barcode_width" value="{{ $setting->barcode_width }}" class="form-input focus:ring-indigo-500/20">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Tinggi (mm)</label>
                                        <input type="number" name="barcode_height" value="{{ $setting->barcode_height }}" class="form-input focus:ring-indigo-500/20">
                                    </div>
                                </div>
                                <label class="flex items-center p-3 border border-gray-100 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                    <input type="checkbox" name="show_price_on_barcode" class="w-4 h-4 text-indigo-500 rounded border-gray-300 focus:ring-indigo-500" {{ $setting->show_price_on_barcode ? 'checked' : '' }} value="1">
                                    <span class="ml-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Tampilkan Harga pada Label</span>
                                </label>
                            </div>

                            {{-- Real-time Barcode Preview --}}
                            <div class="flex flex-col items-center justify-center p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Preview Label Barcode (3:1)</p>
                                <div id="barcode-preview-container" class="bg-white p-3 shadow-sm border border-gray-200 flex flex-col items-center justify-center transition-all duration-300 overflow-hidden text-black" 
                                     style="width: {{ $setting->barcode_width * 3 }}px; height: {{ $setting->barcode_height * 3 }}px;">
                                    {{-- Product Name Placeholder --}}
                                    <div class="text-center font-bold mb-1 leading-tight" style="font-size: 8pt;">NAMA PRODUK CONTOH</div>
                                    
                                    {{-- Simulated Barcode Lines --}}
                                    <div class="w-full h-1/3 bg-repeat-x flex items-center justify-center mb-0.5" 
                                         style="background-image: linear-gradient(90deg, #000 1px, transparent 1px, transparent 2px, #000 2px, #000 4px, transparent 4px, transparent 5px, #000 5px); background-size: 6px 100%;">
                                    </div>
                                    
                                    {{-- Price Placeholder --}}
                                    <div id="barcode-price-preview" class="font-extrabold mb-0.5 {{ $setting->show_price_on_barcode ? '' : 'hidden' }}" style="font-size: 9pt;">
                                        Rp 150.000
                                    </div>

                                    {{-- Code Placeholder --}}
                                    <div class="text-gray-500 font-mono" style="font-size: 6pt;">PROD-12345</div>
                                </div>
                                <p class="text-[9px] text-gray-400 mt-4 text-center">Tampilan di atas adalah simulasi layout cetak</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Preview Section --}}
        <div class="lg:col-span-12 xl:col-span-5 h-fit sticky top-8">
            <div class="bg-gray-900 rounded-[2.5rem] p-6 shadow-2xl border-4 border-gray-800">
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-1 bg-gray-700 rounded-full"></div>
                </div>
                
                {{-- Virtual Receipt Card --}}
                <div class="flex justify-center transition-all duration-300" id="receipt-preview-wrapper">
                    <div id="virtual-receipt" class="bg-white p-6 text-black shadow-inner overflow-hidden border border-gray-200 transition-all duration-300" style="width: {{ $setting->paper_width == 58 ? '240px' : '320px' }}; font-family: 'Courier New', Courier, monospace; min-height: 400px; font-size: {{ $setting->font_size }}px;">
                        <div class="receipt">
                            <!-- Header -->
                            <div id="preview-header-section" class="text-center mb-4 pb-2 border-b border-dashed border-black">
                                @if ($storeProfile && $storeProfile->logo)
                                    <div id="preview-logo-container" class="flex justify-center mb-2 {{ $setting->show_logo ? '' : 'hidden' }}">
                                        <img src="{{ $storeProfile->logo_url }}" class="h-10 w-auto object-contain filter grayscale">
                                    </div>
                                @endif
                                <h1 id="preview-store-name" class="font-bold text-lg uppercase mb-1">
                                    {{ $storeProfile->name ?? 'ARNETA COLLECTION' }}
                                </h1>
                                <p class="mb-1 text-[10px]">{{ $storeProfile->address ?? 'Jl. Contoh No. 123' }}</p>
                                <p class="mb-2 text-[10px]">Telp: {{ $storeProfile->phone ?? '0812-3456-7890' }}</p>
                                <p id="preview-header-text" class="italic pt-2 {{ $setting->receipt_header ? '' : 'hidden' }}">{{ $setting->receipt_header }}</p>
                            </div>

                            <!-- Info -->
                            <div class="text-[10px] border-b border-dashed border-black pb-2 mb-2 space-y-0.5">
                                <div class="flex justify-between">
                                    <span>No: #INV-001</span>
                                    <span>{{ date('d/m/Y') }}</span>
                                </div>
                                <div id="preview-cashier-row" class="flex justify-between {{ $setting->show_cashier_name ? '' : 'hidden' }}">
                                    <span>Kasir:</span>
                                    <span>Admin</span>
                                </div>
                                <div id="preview-customer-row" class="flex justify-between {{ $setting->show_customer_name ? '' : 'hidden' }}">
                                    <span>Plg:</span>
                                    <span>Budi</span>
                                </div>
                            </div>

                            {{-- Sample Items --}}
                            <div class="text-[11px] space-y-2 border-b border-dashed border-black pb-2 mb-2">
                                <div>
                                    <p class="font-bold uppercase">Sample Item Pro</p>
                                    <div class="flex justify-between">
                                        <span>2 x Rp 50.000</span>
                                        <span>Rp 100.000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-[11px] space-y-0.5 mb-4">
                                <div class="flex justify-between font-bold">
                                    <span>TOTAL</span>
                                    <span>Rp 100.000</span>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="text-center text-[11px]">
                                <p id="preview-thank-you" class="font-bold uppercase mb-1 {{ $setting->show_thank_you_note ? '' : 'hidden' }}">TERIMA KASIH</p>
                                <p id="preview-footer-text" class="whitespace-pre-wrap {{ $setting->receipt_footer ? '' : 'hidden' }}">{{ $setting->receipt_footer }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 text-center">
                    <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest">Simulasi Hasil Cetak</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggles
        const connType = document.getElementById('connection_type');
        const qzBox = document.getElementById('qz_settings');
        const browserBox = document.getElementById('browser_instructions');

        connType.addEventListener('change', () => {
            qzBox.classList.toggle('hidden', connType.value !== 'qz');
            browserBox.classList.toggle('hidden', connType.value !== 'browser');
        });

        // Inputs
        const headerIn = document.querySelector('input[name="receipt_header"]');
        const footerIn = document.querySelector('textarea[name="receipt_footer"]');
        
        // Preview Elements
        const headerPre = document.getElementById('preview-header-text');
        const footerPre = document.getElementById('preview-footer-text');

        headerIn.addEventListener('input', () => {
            headerPre.textContent = headerIn.value;
            headerPre.classList.toggle('hidden', !headerIn.value);
        });

        footerIn.addEventListener('input', () => {
            footerPre.textContent = footerIn.value;
            footerPre.classList.toggle('hidden', !footerIn.value);
        });

        // Checkboxes
        const toggles = [
            { name: 'show_logo', el: 'preview-logo-container' },
            { name: 'show_cashier_name', el: 'preview-cashier-row' },
            { name: 'show_customer_name', el: 'preview-customer-row' },
            { name: 'show_thank_you_note', el: 'preview-thank-you' }
        ];

        toggles.forEach(t => {
            const cb = document.querySelector(`input[name="${t.name}"]`);
            cb.addEventListener('change', () => {
                const target = document.getElementById(t.el);
                if (target) target.classList.toggle('hidden', !cb.checked);
            });
        });

        // Paper & Font
        const paperIn = document.querySelector('select[name="paper_width"]');
        const fontIn = document.querySelector('input[name="font_size"]');
        const receipt = document.getElementById('virtual-receipt');

        paperIn.addEventListener('change', () => {
            receipt.style.width = paperIn.value === '58' ? '240px' : '320px';
        });

        fontIn.addEventListener('input', () => {
            receipt.style.fontSize = (fontIn.value || 12) + 'px';
        });

        // Barcode Preview Controls
        const bcWidthIn = document.querySelector('input[name="barcode_width"]');
        const bcHeightIn = document.querySelector('input[name="barcode_height"]');
        const bcPriceCb = document.querySelector('input[name="show_price_on_barcode"]');
        
        const bcPreview = document.getElementById('barcode-preview-container');
        const bcPricePre = document.getElementById('barcode-price-preview');

        const updateBarcodePreview = () => {
            bcPreview.style.width = (bcWidthIn.value * 3) + 'px';
            bcPreview.style.height = (bcHeightIn.value * 3) + 'px';
            bcPricePre.classList.toggle('hidden', !bcPriceCb.checked);
        };

        bcWidthIn.addEventListener('input', updateBarcodePreview);
        bcHeightIn.addEventListener('input', updateBarcodePreview);
        bcPriceCb.addEventListener('change', updateBarcodePreview);
    });
</script>
@endsection
