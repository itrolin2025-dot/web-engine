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
            // QR payment image (e.g. QRIS) shown at checkout for this website
            $table->string('qr_payment')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers_website', function (Blueprint $table) {
            $table->dropColumn('qr_payment');
        });
    }
};
