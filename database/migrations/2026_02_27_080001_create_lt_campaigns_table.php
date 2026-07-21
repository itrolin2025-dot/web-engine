<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lt_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('brand', 20);
            $table->string('objective', 10); // AW, TR, EN, LD, SL
            $table->string('name');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lt_campaigns');
    }
};
