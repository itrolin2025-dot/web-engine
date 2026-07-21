<?php

namespace Database\Seeders;

use App\Models\Modul;
use App\Models\ModulAkses;
use Illuminate\Database\Seeder;

class CustomerModuleSeeder extends Seeder
{
    public function run(): void
    {
        // Add Customers Module
        $customerModul = Modul::updateOrCreate(
            ['kode' => 'customers'],
            [
                'parent_id' => 0,
                'sort_order' => 9, // Adjust sort as needed
                'name'      => 'Customers',
                'icon'      => 'fa-solid fa-users',
                'shortcut'  => 'C',
            ]
        );

        // Add Default Akses (Permissions)
        $akses = ['view', 'add', 'edit', 'delete', 'detail', 'recycle'];
        foreach ($akses as $a) {
            ModulAkses::updateOrCreate(
                [
                    'modul_id' => $customerModul->id,
                    'akses'    => $a,
                ]
            );
        }
    }
}
