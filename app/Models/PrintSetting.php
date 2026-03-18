<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrintSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'show_logo',
        'receipt_header',
        'receipt_footer',
        'show_cashier_name',
        'show_customer_name',
        'auto_print_receipt',
        'paper_width',
        'font_size',
        'show_thank_you_note',
        'connection_type',
        'qz_printer_name',
        'auto_cut',
        'barcode_width',
        'barcode_height',
        'show_price_on_barcode',
    ];
}
