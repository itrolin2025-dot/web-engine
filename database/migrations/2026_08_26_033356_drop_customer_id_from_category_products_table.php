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
        Schema::table('category_products', function (Blueprint $table) {
            if (Schema::hasColumn('category_products', 'customer_id')) {
                // Drop foreign key if exists
                try {
                    $table->dropForeign(['customer_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key was already dropped or doesn't exist
                }
                $table->dropColumn('customer_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_products', function (Blueprint $table) {
            if (!Schema::hasColumn('category_products', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('customers_id');
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            }
        });
    }
};
