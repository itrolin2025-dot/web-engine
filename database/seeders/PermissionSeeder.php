<?php

namespace Database\Seeders;

use App\Models\Modul;
use App\Models\ModulAkses;
use App\Models\Permissions;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Semua modul yang ada di aplikasi (berdasarkan routes/admin.php).
     *
     * shortcut 'side' = tampil di sidebar
     * shortcut lain   = tidak tampil di sidebar
     */
    protected array $modules = [
        // === Dashboard (special: route tanpa .index) ===
        ['kode' => 'dashboard',         'name' => 'Dashboard',          'icon' => 'fa-solid fa-gauge-high',       'sort_order' => 1,  'parent' => null, 'shortcut' => 'left'],

        // === Main Modules ===
        ['kode' => 'template',          'name' => 'Template',           'icon' => 'fa-solid fa-paint-roller',     'sort_order' => 8, 'parent' => null, 'shortcut' => 'side'],
        ['kode' => 'customers',         'name' => 'Customers',          'icon' => 'fa-solid fa-user-group',       'sort_order' => 9,  'parent' => null, 'shortcut' => 'side'],
        ['kode' => 'customers-website', 'name' => 'Customers Website',  'icon' => 'fa-solid fa-globe',            'sort_order' => 10, 'parent' => null, 'shortcut' => 'side'],
        ['kode' => 'article-category',  'name' => 'Article Categories', 'icon' => 'fa-solid fa-layer-group',      'sort_order' => 11, 'parent' => null, 'shortcut' => 'left'],
        ['kode' => 'articles',          'name' => 'Articles',           'icon' => 'fa-solid fa-file-lines',       'sort_order' => 12, 'parent' => null, 'shortcut' => 'side'],
        ['kode' => 'category-product',  'name' => 'Product Categories', 'icon' => 'fa-solid fa-box',              'sort_order' => 13, 'parent' => null, 'shortcut' => 'left'],
        ['kode' => 'products',          'name' => 'Products',           'icon' => 'fa-solid fa-box-open',         'sort_order' => 14, 'parent' => null, 'shortcut' => 'side'],

        // === Admin Modules ===
        ['kode' => 'users',             'name' => 'Users',              'icon' => 'fa-solid fa-users',            'sort_order' => 90, 'parent' => null, 'shortcut' => 'left'],
        ['kode' => 'roles',             'name' => 'Roles',              'icon' => 'fa-solid fa-user-shield',      'sort_order' => 91, 'parent' => null, 'shortcut' => 'left'],
        ['kode' => 'modul',             'name' => 'Modul',              'icon' => 'fa-solid fa-puzzle-piece',     'sort_order' => 92, 'parent' => null, 'shortcut' => 'left'],
    ];

    /**
     * Akses (permission) standar untuk setiap modul.
     */
    protected array $accesses = ['view', 'add', 'edit', 'delete', 'detail', 'recycle'];

    /**
     * Label untuk setiap akses.
     */
    protected array $accessLabels = [
        'view'    => 'View',
        'add'     => 'Add',
        'edit'    => 'Edit',
        'delete'  => 'Delete',
        'detail'  => 'Detail',
        'recycle' => 'Recycle',
    ];

    public function run(): void
    {
        // Hapus module yang TIDAK ada di array ini (beserta modul_akses & permissions terkait)
        $validKodes = array_column($this->modules, 'kode');
        $orphanModuls = Modul::whereNotIn('kode', $validKodes)->pluck('id');

        if ($orphanModuls->isNotEmpty()) {
            ModulAkses::whereIn('modul_id', $orphanModuls)->delete();
            Modul::whereIn('id', $orphanModuls)->delete();
        }

        foreach ($this->modules as $modulData) {
            // 1. Insert / Update moduls
            $modul = Modul::updateOrCreate(
                ['kode' => $modulData['kode']],
                [
                    'parent_id'  => $modulData['parent'],
                    'sort_order' => $modulData['sort_order'],
                    'name'       => $modulData['name'],
                    'icon'       => $modulData['icon'],
                    'shortcut'   => $modulData['shortcut'],
                ]
            );

            // 2. Insert / Update modul_akses
            foreach ($this->accesses as $akses) {
                ModulAkses::updateOrCreate(
                    ['modul_id' => $modul->id, 'akses' => $akses]
                );
            }

            // 3. Insert / Update permissions (table: permissions)
            foreach ($this->accesses as $akses) {
                $permissionName = $modulData['kode'] . '.' . $akses;
                $permissionLabel = $this->accessLabels[$akses] . ' ' . $modulData['name'];

                Permissions::updateOrCreate(
                    ['name' => $permissionName],
                    [
                        'label' => $permissionLabel,
                    ]
                );
            }
        }
    }
}
