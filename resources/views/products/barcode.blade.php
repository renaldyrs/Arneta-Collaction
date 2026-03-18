<!DOCTYPE html>
<html lang="id">
<@php
    $printSetting = \App\Models\PrintSetting::first() ?? (object)[
        'barcode_width' => 40,
        'barcode_height' => 30,
        'show_price_on_barcode' => true
    ];
@endphp
<head>
    <meta charset="UTF-8">
    <title>Cetak Barcode - {{ $products->first()->name ?? '' }}</title>
    <style>
        @page {
            size: {{ $printSetting->barcode_width }}mm {{ $printSetting->barcode_height }}mm;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: white;
        }
        .barcode-container {
            width: {{ $printSetting->barcode_width }}mm;
            height: {{ $printSetting->barcode_height }}mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            box-sizing: border-box;
            padding: 1mm;
            page-break-after: always;
            overflow: hidden;
        }
        .product-name {
            font-size: 8pt;
            font-weight: bold;
            line-height: 1.1;
            margin-bottom: 1mm;
            height: 2.4em;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .barcode-svg {
            width: 100% !important;
            height: auto !important;
            max-height: {{ $printSetting->barcode_height * 0.4 }}mm;
        }
        .product-price {
            font-size: 9pt;
            font-weight: 800;
            margin-top: 1mm;
        }
        .product-code {
            font-size: 6pt;
            color: #555;
            margin-top: 0.5mm;
        }
        @media screen {
            body {
                background-color: #f0f2f5;
                padding: 20px;
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                justify-content: center;
            }
            .barcode-container {
                background-color: white;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                border-radius: 4px;
                border: 1px solid #ddd;
            }
            .no-print {
                width: 100%;
                margin-bottom: 20px;
                text-align: center;
                background: white;
                padding: 15px;
                border-radius: 12px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            }
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body>
    <div class="no-print">
        <div style="margin-bottom: 15px; font-weight: bold; font-size: 1.2rem; color: #1a202c;">Preview Barcode Cetak</div>
        <div style="display: flex; justify-content: center; gap: 10px;">
            <button onclick="window.print()" style="padding:10px 20px; background:#10b981; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold; display:flex; align-items:center; gap:8px;">
                <span>🖨️ Cetak Sekarang</span>
            </button>
            <button onclick="window.close()" style="padding:10px 20px; background:#ef4444; color:white; border:none; border-radius:8px; cursor:pointer; font-weight:bold;">
                Batal
            </button>
        </div>
        <p style="margin-top:15px; color: #718096; font-size: 0.9rem;">Mencetak <strong>{{ $quantity }}</strong> label untuk produk: <strong>{{ $products->first()->name }}</strong></p>
    </div>

    @foreach ($products as $product)
        @for ($i = 0; $i < $quantity; $i++)
            <div class="barcode-container">
                <div class="product-name">{{ $product->name }}</div>
                <svg class="barcode-svg"
                    data-value="{{ $product->code }}"
                    data-text="{{ $product->code }}">
                </svg>
                @if ($printSetting->show_price_on_barcode)
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                @endif
                <div class="product-code">{{ $product->code }}</div>
            </div>
        @endfor
    @endforeach

    <script>
        window.onload = function() {
            const barcodes = document.querySelectorAll('.barcode-svg');
            barcodes.forEach(el => {
                JsBarcode(el, el.getAttribute('data-value'), {
                    format: "CODE128",
                    width: 2,
                    height: 40,
                    displayValue: false,
                    margin: 0
                });
            });
        };
    </script>
</body>
</html>
