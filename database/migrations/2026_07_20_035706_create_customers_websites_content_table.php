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
        Schema::create('customers_websites_content', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customers_website_id');
            $table->string('tagline')->nullable();
            $table->string('tagline_al')->nullable();
            $table->text('header')->nullable();
            $table->text('header_al')->nullable();
            $table->text('description')->nullable();
            $table->text('description_al')->nullable();
            $table->string('website_url')->nullable();
            $table->text('logo')->nullable();
            $table->text('logo_mobile')->nullable();
            $table->text('banner')->nullable();
            $table->text('banner_mobile')->nullable();
            $table->text('image')->nullable();
            $table->text('image_mobile')->nullable();
            $table->string('about_title')->nullable();
            $table->string('about_title_al')->nullable();
            $table->text('about_desc')->nullable();
            $table->text('about_desc_al')->nullable();
            $table->text('about_image')->nullable();
            $table->bigInteger('gallery_id')->nullable();
            $table->bigInteger('gallery_id_al')->nullable();
            $table->boolean('show_product')->nullable();
            $table->boolean('show_reviews')->nullable();
            $table->string('footer_title')->nullable();
            $table->string('footer_title_al')->nullable();
            $table->text('footer_desc')->nullable();
            $table->text('footer_desc_al')->nullable();
            $table->text('footer_image')->nullable();
            $table->text('footer_text')->nullable();
            $table->text('footer_text_al')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers_websites_content');
    }
};
