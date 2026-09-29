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

        // Data untuk dropdown notification di header admin (tab: All / Transaction / Content / Other).
        // Menggunakan view composer agar hanya berjalan saat header benar-benar dirender.
        View::composer('admin.layouts.header', function ($view) {
            $view->with($this->getNotificationData());
        });
    }

    /**
     * Build notification data for the admin header dropdown.
     */
    private function getNotificationData(): array
    {
        // Default kosong (aman untuk fresh DB / sqlite test)
        $data = [
            'notifTransactions'     => collect(),
            'notifContentLogs'      => collect(),
            'notifOtherLogs'        => collect(),
            'notifAll'              => collect(),
            'notifTransactionCount' => 0,
            'notifContentCount'     => 0,
            'notifOtherCount'       => 0,
            'notifTotalCount'       => 0,
        ];

        if (!Schema::hasTable('transactions') || !Schema::hasTable('activity_logs')) {
            return $data;
        }

        // Label aksi untuk log
        $actionLabels = [
            'create'  => 'Created',
            'update'  => 'Updated',
            'delete'  => 'Deleted',
            'restore' => 'Restored',
        ];

        $logItem = function ($log) use ($actionLabels) {
            $payload = $log->payload ?? [];
            $name = $payload['name'] ?? $payload['code'] ?? $payload['nama'] ?? ('#' . $log->transaction_id);

            return [
                'title'    => ucfirst($log->module) . ' ' . ($actionLabels[$log->action] ?? str_replace('_', ' ', ucfirst($log->action))),
                'subtitle' => $name,
                'icon'     => 'fa-solid fa-pen-to-square',
                'color'    => 'info',
                'url'      => null,
            ];
        };

        // TRANSACTIONS: transaksi yang status pembayarannya masih Pending
        $pendingQuery = \App\Models\Transaction::where('status', 'Pending');

        $data['notifTransactions'] = (clone $pendingQuery)
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map(function ($t) {
                return [
                    'title'    => $t->code,
                    'subtitle' => 'waiting for approval',
                    'icon'     => 'fa-solid fa-receipt',
                    'color'    => 'warning',
                    'url'      => route('admin.transactions.index'),
                ];
            });

        $data['notifTransactionCount'] = (clone $pendingQuery)->count();

        // CONTENT: log aktivitas modul konten
        $contentModules = ['articles', 'article-category', 'products', 'category-product', 'tags', 'template'];

        $data['notifContentLogs'] = \App\Models\ActivityLog::whereIn('module', $contentModules)
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map($logItem);

        $data['notifContentCount'] = \App\Models\ActivityLog::whereIn('module', $contentModules)->count();

        // OTHER: log aktivitas modul lainnya (customers, users, modul, dsb.)
        $data['notifOtherLogs'] = \App\Models\ActivityLog::whereNotIn('module', array_merge($contentModules, ['transactions']))
            ->orderByDesc('id')
            ->limit(10)
            ->get()
            ->map($logItem);

        $data['notifOtherCount'] = \App\Models\ActivityLog::whereNotIn('module', array_merge($contentModules, ['transactions']))->count();

        $data['notifTotalCount'] = $data['notifTransactionCount'] + $data['notifContentCount'] + $data['notifOtherCount'];

        // Gabungan untuk tab All
        $data['notifAll'] = $data['notifTransactions']
            ->concat($data['notifContentLogs'])
            ->concat($data['notifOtherLogs'])
            ->values();

        return $data;
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
