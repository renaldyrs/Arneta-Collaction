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
        Schema::table('print_settings', function (Blueprint $table) {
            $table->integer('paper_width')->default(80)->after('auto_print_receipt'); // 58 or 80
            $table->integer('font_size')->default(12)->after('paper_width');
            $table->boolean('show_thank_you_note')->default(true)->after('font_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('print_settings', function (Blueprint $table) {
            $table->dropColumn(['paper_width', 'font_size', 'show_thank_you_note']);
        });
    }
};
