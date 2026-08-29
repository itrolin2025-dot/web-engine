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
            if (!Schema::hasColumn('category_products', 'customers_id')) {
                $table->unsignedBigInteger('customers_id')->nullable()->after('id');
                $table->foreign('customers_id')->references('id')->on('customers')->onDelete('set null');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'customers_id')) {
                $table->unsignedBigInteger('customers_id')->nullable()->after('id');
                $table->foreign('customers_id')->references('id')->on('customers')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_products', function (Blueprint $table) {
            if (Schema::hasColumn('category_products', 'customers_id')) {
                $table->dropForeign(['customers_id']);
                $table->dropColumn('customers_id');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'customers_id')) {
                $table->dropForeign(['customers_id']);
                $table->dropColumn('customers_id');
            }
        });
    }
};
