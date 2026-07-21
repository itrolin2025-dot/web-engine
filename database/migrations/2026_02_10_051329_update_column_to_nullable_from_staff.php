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
        Schema::table('staffs', function (Blueprint $table) {
            $table->string('code')->nullable()->change();
            $table->string('position')->nullable()->change();
            $table->date('date_join')->nullable()->change();
            $table->string('photo')->nullable()->change();
            $table->string('status')->nullable()->change();
            $table->unsignedBigInteger('departemen_id')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->text('phone')->nullable()->change();
            $table->boolean('is_active')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->string('code')->nullable(false)->change();
            $table->string('position')->nullable(false)->change();
            $table->date('date_join')->nullable(false)->change();
            $table->string('photo')->nullable(false)->change();
            $table->string('status')->nullable(false)->change();
            $table->unsignedBigInteger('departemen_id')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->text('phone')->nullable(false)->change();
            $table->boolean('is_active')->nullable(false)->change();
        });
    }
};
