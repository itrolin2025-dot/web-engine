<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lt_creatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('adset_id');
            $table->string('ref', 10)->unique(); // e.g. A3X7
            $table->string('name');
            $table->string('format')->default('Video'); // Video, Image, Carousel
            $table->string('no', 10)->default('01');
            $table->decimal('spend', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lt_creatives');
    }
};
