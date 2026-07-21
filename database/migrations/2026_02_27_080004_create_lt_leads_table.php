<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lt_leads', function (Blueprint $table) {
            $table->id();
            $table->date('lead_date')->nullable();
            $table->string('ref', 10)->nullable(); // ref token dari creative
            $table->string('title', 10)->default('Mr.'); // Mr. Mrs. Ms.
            $table->string('name');
            $table->string('wa', 30)->nullable();
            $table->string('status')->default('Fresh Lead');
            // Fresh Lead | In Discussion | Qualified | Closed Won | Closed Lost
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lt_leads');
    }
};
