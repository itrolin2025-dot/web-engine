<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers_websites_product_layout', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('templates_section_id')->nullable();
            $table->bigInteger('customers_website_id')->nullable();
            $table->text('content')->nullable();
            $table->boolean('status')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers_websites_product_layout');
    }
};
