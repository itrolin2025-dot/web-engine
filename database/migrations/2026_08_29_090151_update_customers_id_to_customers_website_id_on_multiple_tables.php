<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['article_categories', 'articles', 'products', 'category_products'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'customers_id')) {
                    if ($tableName === 'products' || $tableName === 'category_products') {
                        $table->dropForeign(['customers_id']);
                    }
                    $table->dropColumn('customers_id');
                }
                
                if (!Schema::hasColumn($tableName, 'customers_website_id')) {
                    $table->unsignedBigInteger('customers_website_id')->after('id')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        $tables = ['article_categories', 'articles', 'products', 'category_products'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'customers_id')) {
                    $table->unsignedBigInteger('customers_id')->after('id')->nullable();
                    
                    if ($tableName === 'products' || $tableName === 'category_products') {
                        $table->foreign('customers_id')->references('id')->on('customers')->onDelete('set null');
                    }
                }

                if (Schema::hasColumn($tableName, 'customers_website_id')) {
                    $table->dropColumn('customers_website_id');
                }
            });
        }
    }
};
