<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom social media untuk modul Customers Website
     * (form add/edit & duplicate di CustomersWebController).
     */
    private array $socialColumns = [
        'instagram' => ['after' => 'is_selected'],
        'tiktok'    => ['after' => 'instagram'],
        'facebook'  => ['after' => 'tiktok'],
        'x'         => ['after' => 'facebook'],
        'threads'   => ['after' => 'x'],
        'shopee'    => ['after' => 'threads'],
        'tokopedia' => ['after' => 'shopee'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers_website', function (Blueprint $table) {
            foreach ($this->socialColumns as $column => $meta) {
                if (!Schema::hasColumn('customers_website', $column)) {
                    $table->string($column)->nullable()->after($meta['after']);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers_website', function (Blueprint $table) {
            foreach (array_reverse($this->socialColumns) as $column => $meta) {
                if (Schema::hasColumn('customers_website', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
