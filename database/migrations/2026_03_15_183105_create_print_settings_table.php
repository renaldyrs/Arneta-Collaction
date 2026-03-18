<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('print_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('show_logo')->default(true);
            $table->string('receipt_header')->nullable();
            $table->string('receipt_footer')->nullable();
            $table->boolean('show_cashier_name')->default(true);
            $table->boolean('show_customer_name')->default(true);
            $table->boolean('auto_print_receipt')->default(false);
            $table->integer('barcode_width')->default(40); // mm
            $table->integer('barcode_height')->default(30); // mm
            $table->boolean('show_price_on_barcode')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_settings');
    }
};
