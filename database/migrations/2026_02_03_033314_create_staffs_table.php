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
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignId('departemen_id')->constrained('departemens')->onDelete('cascade');
            $table->string('position');
            $table->string('email');
            $table->string('phone');
            $table->text('address');
            $table->date('date_join');
            $table->text('photo')->nullable();
            $table->text('status');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->bigInteger('created_by')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
