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
        Schema::table('customers_website', function (Blueprint $table) {
            // Drop customer_type: requested to be removed (only shown in list, never needed)
            if (Schema::hasColumn('customers_website', 'customer_type')) {
                $table->dropColumn('customer_type');
            }

            // Selected Product: starred websites (used as highlighted/selected products)
            if (!Schema::hasColumn('customers_website', 'is_selected')) {
                $table->boolean('is_selected')->default(0)->after('qr_payment');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers_website', function (Blueprint $table) {
            if (!Schema::hasColumn('customers_website', 'customer_type')) {
                $table->string('customer_type')->nullable()->after('customer_id');
            }

            if (Schema::hasColumn('customers_website', 'is_selected')) {
                $table->dropColumn('is_selected');
            }
        });
    }
};
