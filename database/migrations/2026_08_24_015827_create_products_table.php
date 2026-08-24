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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_products_id');
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('category_products_id')->references('id')->on('category_products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
