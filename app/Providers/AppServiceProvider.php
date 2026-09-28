<?php

namespace App\Providers;
use App\Models\Modul;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Guard: saat tabel belum ada (fresh DB / test sqlite), lewati agar
        // aplikasi tetap bisa boot dan migrate bisa berjalan.
        if (Schema::hasTable('moduls')) {
            $menus = Modul::orderBy('sort_order')->get();

            $menuTree = $this->buildTree($menus);

            // Bagikan ke semua view
            View::share('menus', $menuTree);
        }
    }

    private function buildTree($menus)
    {
        $items = [];

        // buat array index berdasarkan ID
        foreach ($menus as $menu) {
            $items[$menu->id] = [
                'id'        => $menu->id,
                'name'      => $menu->name,
                'kode'      => $menu->kode,
                'parent_id' => $menu->parent_id,
                'children'  => []
            ];
        }

        $tree = [];

        foreach ($items as $id => &$node) {

            // Jika parent_id = id, maka dia parent utama (root)
            if ($node['parent_id'] == $node['id']) {
                $tree[] = &$node;
            }

            // Jika parent_id bukan dirinya, dan parent tersedia → jadikan child
            elseif (isset($items[$node['parent_id']])) {
                $items[$node['parent_id']]['children'][] = &$node;
            }

            // Jika parent tidak ditemukan (misal null), tetap tampil sebagai menu utama
            elseif ($node['parent_id'] == null || $node['parent_id'] == 0) {
                $tree[] = &$node;
            }
        }

        return $tree;
    }



}
