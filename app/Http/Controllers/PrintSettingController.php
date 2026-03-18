<?php

namespace App\Http\Controllers;

use App\Models\PrintSetting;
use Illuminate\Http\Request;

class PrintSettingController extends Controller
{
    public function index()
    {
        $setting = PrintSetting::firstOrCreate([], [
            'show_logo' => true,
            'receipt_header' => 'Terima kasih atas kunjungan Anda!',
            'receipt_footer' => 'Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.',
            'show_cashier_name' => true,
            'show_customer_name' => true,
            'auto_print_receipt' => false,
            'paper_width' => 80,
            'font_size' => 12,
            'show_thank_you_note' => true,
            'connection_type' => 'browser',
            'qz_printer_name' => '',
            'auto_cut' => false,
            'barcode_width' => 40,
            'barcode_height' => 30,
            'show_price_on_barcode' => true,
        ]);

        $storeProfile = \App\Models\StoreProfile::first();
        return view('settings.print', compact('setting', 'storeProfile'));
    }

    public function update(Request $request)
    {
        $setting = PrintSetting::first();

        $data = $request->validate([
            'show_logo' => 'boolean',
            'receipt_header' => 'nullable|string|max:255',
            'receipt_footer' => 'nullable|string|max:255',
            'show_cashier_name' => 'boolean',
            'show_customer_name' => 'boolean',
            'auto_print_receipt' => 'boolean',
            'paper_width' => 'required|integer|in:58,80',
            'font_size' => 'required|integer|min:8|max:24',
            'show_thank_you_note' => 'boolean',
            'connection_type' => 'required|string|in:browser,qz',
            'qz_printer_name' => 'nullable|string|max:255',
            'auto_cut' => 'boolean',
            'barcode_width' => 'required|integer|min:10|max:100',
            'barcode_height' => 'required|integer|min:10|max:100',
            'show_price_on_barcode' => 'boolean',
        ]);

        // Handle booleans (since checkboxes might not be sent if unchecked)
        $booleans = ['show_logo', 'show_cashier_name', 'show_customer_name', 'auto_print_receipt', 'show_thank_you_note', 'auto_cut', 'show_price_on_barcode'];
        foreach ($booleans as $bool) {
            $data[$bool] = $request->has($bool);
        }

        $setting->update($data);

        return redirect()->back()->with('success', 'Pengaturan printer berhasil diperbarui.');
    }

    public function testPrint()
    {
        $transaction = (object)[
            'invoice_number' => 'INV-TEST-' . date('Ymd-His'),
            'created_at' => now(),
            'total_amount' => 150000,
            'payment_amount' => 200000,
            'change_amount' => 50000,
            'discount_amount' => 10000,
            'user' => (object)['name' => auth()->user()->name ?? 'Admin'],
            'customer' => (object)['name' => 'Pelanggan Test'],
            'paymentMethod' => (object)['name' => 'Tunai'],
            'details' => collect([
                (object)[
                    'product' => (object)['name' => 'Produk Test Premium'],
                    'quantity' => 2,
                    'price' => 75000,
                    'subtotal' => 150000,
                    'size' => 'XL'
                ]
            ])
        ];

        $storeProfile = \App\Models\StoreProfile::first();
        $printSetting = PrintSetting::first();

        return view('cashier.print', compact('transaction', 'storeProfile', 'printSetting'))->with('is_test', true);
    }
}
