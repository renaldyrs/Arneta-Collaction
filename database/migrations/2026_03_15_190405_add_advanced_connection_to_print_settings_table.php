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
            $table->string('connection_type')->default('browser')->after('show_thank_you_note'); // 'browser' or 'qz'
            $table->string('qz_printer_name')->nullable()->after('connection_type');
            $table->boolean('auto_cut')->default(false)->after('qz_printer_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('print_settings', function (Blueprint $table) {
            $table->dropColumn(['connection_type', 'qz_printer_name', 'auto_cut']);
        });
    }
};
