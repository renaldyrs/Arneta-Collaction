<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambah kolom discount dan customer ke tabel transactions
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null')->after('user_id');
            $table->foreignId('discount_id')->nullable()->constrained('discounts')->onDelete('set null')->after('customer_id');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('change_amount');
            $table->string('notes')->nullable()->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['discount_id']);
            $table->dropColumn(['customer_id', 'discount_id', 'discount_amount', 'notes']);
        });
    }
};
