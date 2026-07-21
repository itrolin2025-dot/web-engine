<?php

use App\Models\RolePermission;
use App\Models\ModulAkses;
use Illuminate\Support\Facades\DB;



function canAccess($modul_kode, $role_id, $akses)
{
    // return cache()->remember(
    //     "access_{$role_id}_{$modul_kode}_{$akses}",
    //     now()->addMinutes(10),
    //     function () use ($role_id, $modul_kode, $akses) {
                
                // Akses Super Admin
                if($role_id == 0){
                    
                    return true; //SEMUA AKSES DIBUKA

                }else{

                    // pengecekan apakah memiliki akses atau tidak
                    return DB::table('role_permission as rp')
                        ->join('modul_akses as ma', 'ma.id', '=', 'rp.modul_akses_id')
                        ->join('moduls as m', 'ma.modul_id', '=', 'm.id')
                        ->where('rp.role_id', $role_id)
                        ->where('m.kode', $modul_kode)
                        ->where('ma.akses', $akses)
                        ->where('rp.deleted_at', null)
                        ->exists(); // <-- true / false
                }
                
        // }
    // );
}