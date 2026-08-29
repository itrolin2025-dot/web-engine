<?php

namespace Database\Seeders;

use App\Models\Modul;
use App\Models\ModulAkses;
use Illuminate\Database\Seeder;

class ProductArticleModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'kode' => 'category-product',
                'name' => 'Product Categories',
                'icon' => 'fa-solid fa-box',
                'sort_order' => 10,
            ],
            [
                'kode' => 'products',
                'name' => 'Products',
                'icon' => 'fa-solid fa-box-open',
                'sort_order' => 11,
            ],
            [
                'kode' => 'article-category',
                'name' => 'Article Categories',
                'icon' => 'fa-solid fa-layer-group',
                'sort_order' => 12,
            ],
            [
                'kode' => 'articles',
                'name' => 'Articles',
                'icon' => 'fa-solid fa-file-lines',
                'sort_order' => 13,
            ],
        ];

        $akses = ['view', 'add', 'edit', 'delete', 'detail', 'recycle'];

        foreach ($modules as $m) {
            $modul = Modul::updateOrCreate(
                ['kode' => $m['kode']],
                [
                    'parent_id'  => null,
                    'sort_order' => $m['sort_order'],
                    'name'       => $m['name'],
                    'icon'       => $m['icon'],
                    'shortcut'   => strtoupper(substr($m['name'], 0, 1)),
                ]
            );

            foreach ($akses as $a) {
                ModulAkses::updateOrCreate([
                    'modul_id' => $modul->id,
                    'akses'    => $a,
                ]);
            }
        }
    }
}
