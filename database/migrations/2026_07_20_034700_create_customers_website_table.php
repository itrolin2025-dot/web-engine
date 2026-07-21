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
        Schema::create('customers_website', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->bigInteger('template_id');
            $table->string('title')->nullable();
            $table->string('domain')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->nullable();
            // $table->string('tagline')->nullable();
            // $table->string('tagline_al')->nullable();
            // $table->text('header')->nullable();
            // $table->text('header_al')->nullable();
            // $table->text('description')->nullable();
            // $table->text('description_al')->nullable();
            // $table->text('about')->nullable();
            // $table->text('about_al')->nullable();
            // $table->string('website_url')->nullable();
            // $table->string('logo')->nullable();
            // $table->string('logo_mobile')->nullable();
            // $table->string('banner')->nullable();
            // $table->string('banner_mobile')->nullable();
            // $table->string('image')->nullable();
            // $table->string('image_mobile')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers_website');
    }
};
