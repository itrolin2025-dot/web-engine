<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->unsignedBigInteger('customers_website_id');

            // Customer information
            $table->string('customer_name');
            $table->text('customer_address')->nullable();

            // Shipping information
            $table->text('shipping_address')->nullable();
            $table->string('shipping_courier', 100)->nullable();
            $table->string('shipping_tracking_number', 100)->nullable();
            $table->decimal('shipping_cost', 15, 2)->default(0);
            $table->string('shipping_status', 50)->default('Pending');

            // Totals
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);

            // Transaction status: Pending / Paid / Shipped / Completed / Cancelled
            $table->string('status', 50)->default('Pending');

            $table->timestamps();
            $table->softDeletes();
            $table->foreign('customers_website_id')
                ->references('id')->on('customers_website')
                ->restrictOnDelete();

            $table->index('customers_website_id');
        });

        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->unsignedBigInteger('product_id')->nullable();

            // Product snapshot (survives product rename/deletion)
            $table->string('product_name');
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('qty')->default(1);
            $table->decimal('subtotal', 15, 2)->default(0);

            $table->timestamps();
        });

        // Menu entry (sidebar) + available permissions for the new module.
        // No role_permission seeding: other modules (customers, products, ...) do not
        // have explicit grants either — super admin bypasses the check via role_id 0.
        $modulId = DB::table('moduls')->insertGetId([
            'parent_id'  => null,
            'sort_order' => 15,
            'kode'       => 'transactions',
            'name'       => 'Transaksi',
            'icon'       => 'fa-solid fa-receipt',
            'shortcut'   => 'side',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        foreach (['view', 'add', 'edit', 'delete', 'detail', 'recycle'] as $akses) {
            DB::table('modul_akses')->insert([
                'modul_id'   => $modulId,
                'akses'      => $akses,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('transactions');

        $modul = DB::table('moduls')->where('kode', 'transactions')->first();
        if ($modul) {
            DB::table('modul_akses')->where('modul_id', $modul->id)->delete();
            DB::table('moduls')->where('id', $modul->id)->delete();
        }
    }
};
