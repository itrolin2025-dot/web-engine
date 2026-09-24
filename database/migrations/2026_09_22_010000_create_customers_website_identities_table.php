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
        Schema::create('customers_website_identities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customers_website_id');
            $table->string('logo')->nullable();
            $table->string('primary_color', 20)->nullable();
            $table->string('secondary_color', 20)->nullable();
            $table->string('accent_color', 20)->nullable();
            $table->string('primary_font', 100)->nullable();
            $table->string('secondary_font', 100)->nullable();
            $table->string('font_color', 20)->nullable();
            $table->timestamps();

            $table->foreign('customers_website_id')
                ->references('id')->on('customers_website')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers_website_identities');
    }
};
