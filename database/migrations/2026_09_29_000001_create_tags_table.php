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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique();
            $table->string('nama', 255);

            // Type: template / section / other
            $table->string('type', 50)->default('other')->index();

            $table->timestamps();
            $table->softDeletes();
        });

        // Menu entry (sidebar) + available permissions for the new module.
        // No role_permission seeding: other modules (customers, products, ...) do not
        // have explicit grants either — super admin bypasses the check via role_id 0.
        $modulId = DB::table('moduls')->insertGetId([
            'parent_id'  => null,
            'sort_order' => 16,
            'kode'       => 'tags',
            'name'       => 'Tag',
            'icon'       => 'fa-solid fa-tags',
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
        Schema::dropIfExists('tags');

        $modul = DB::table('moduls')->where('kode', 'tags')->first();
        if ($modul) {
            DB::table('modul_akses')->where('modul_id', $modul->id)->delete();
            DB::table('moduls')->where('id', $modul->id)->delete();
        }
    }
};
