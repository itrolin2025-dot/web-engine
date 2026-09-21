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
            $table->string('customer_type')->nullable()->after('customer_id');
            $table->unsignedBigInteger('template_id')->nullable()->change();
            $table->string('instagram')->nullable()->after('qr_payment');
            $table->string('tiktok')->nullable()->after('instagram');
            $table->string('facebook')->nullable()->after('tiktok');
            $table->string('x')->nullable()->after('facebook');
            $table->string('threads')->nullable()->after('x');
            $table->string('shopee')->nullable()->after('threads');
            $table->string('tokopedia')->nullable()->after('shopee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers_website', function (Blueprint $table) {
            $table->dropColumn([
                'customer_type',
                'instagram',
                'tiktok',
                'facebook',
                'x',
                'threads',
                'shopee',
                'tokopedia',
            ]);
        });
    }
};
