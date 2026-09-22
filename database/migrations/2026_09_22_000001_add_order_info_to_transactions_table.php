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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('customer_phone', 50)->nullable()->after('customer_name');
            $table->string('customer_email', 255)->nullable()->after('customer_phone');
            $table->string('payment_method', 100)->nullable()->after('shipping_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['customer_phone', 'customer_email', 'payment_method']);
        });
    }
};
