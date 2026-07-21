<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\ModulAkses;
use App\Models\Icon;
use App\Models\Permissions;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ModulController extends Controller
{
    protected $modul        = "modul";
    protected $path         = "modul";
    protected $modul_name   = "Moduls";
    protected $role_id;

    public function __construct()
    {
        $this->role_id = auth()->user()->role_id;
    }

    public function index()
    {
        if (canAccess($this->modul, $this->role_id, 'view') == false) {
            return redirect()->route('dashboard');
        }

        $parents = Modul::whereNull('parent_id')->get();

        return view($this->modul.'.index', [
            'parents'       => $parents,
            'canAdd'        => canAccess($this->modul, $this->role_id, 'add'),
            'canEdit'       => canAccess($this->modul, $this->role_id, 'edit'),
            'canDelete'     => canAccess($this->modul, $this->role_id, 'delete'),
            'canDetail'     => canAccess($this->modul, $this->role_id, 'detail'),
            'canRecycle'    => canAccess($this->modul, $this->role_id, 'recycle'),
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'List'
        ]);
        
    }

    public function recycle()
    {
        if (canAccess($this->modul, $this->role_id, 'recycle') == false) {
            if (canAccess($this->modul, $this->role_id, 'view') == true) {
                return redirect()->route('modul.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        $parents = Modul::whereNull('parent_id')->get();

        return view($this->modul.'.recycle', 
            [
                'parents'       => $parents,
                'modul'         => $this->modul,
                'modul_path'    => $this->path,
                'modul_name'    => $this->modul_name,
                'modul_type'    => 'Recycle'
            ]);
        
    }

    public function create()
    {
        if (canAccess($this->modul, $this->role_id, 'add') == false) {
            if (canAccess($this->modul, $this->role_id, 'view') == true) {
                return redirect()->route('modul.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        $parents        = Modul::whereNull('parent_id')->get();
        $permissions    = Permissions::all();
        $icons          = Icon::all();

        return view($this->modul.'.create', [
            'parents'       => $parents,
            'icons'         => $icons,
            'permissions'   => $permissions,
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'Create'
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'kode'       => 'required|string',
            'name'       => 'required|string',
            'akses'      => 'array'
        ]);

        DB::beginTransaction();

        try {
            // 1. Insert Modul
            $Modul = Modul::create([
                'kode' => $request->kode,
                'name' => $request->name,
                'parent_id' => $request->parent_id,
                'icon'      => $request->icon ?: 'fa-solid fa-circle-question',
                'shortcut'  => $request->shortcut
            ]);

            // 2. Insert accesses (add/edit/delete)
            if ($request->akses) {
                foreach ($request->akses as $akses) {
                    ModulAkses::create([
                        'modul_id'  => $Modul->id,
                        'akses'     => $akses,
                    ]);
                }
            }

            // 3. Save Proses ke "activity_logs"
            ActivityLogger::log(
                $this->modul,
                'create',
                $Modul->id,
                ['name' => $Modul->name],
                auth()->id()
            );

            DB::commit();

            return redirect()->route($this->modul.'.index')->with('success', 'Data has been created successfully.');
        
        } catch (\Exception $e) {
            DB::rollBack();

            // optionally log error here

            return redirect()->route($this->modul.'.index')->with('error', 'Failed to create data. Please try again.
             : '.$e->getMessage());
        }
    }

    public function edit(Modul $modul_data)
    {

        if (canAccess($this->modul, $this->role_id, 'edit') == false) {
            if (canAccess($this->modul, $this->role_id, 'view') == true) {
                return redirect()->route($this->modul.'.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        $selectedAccess = $modul_data->modulAkses()->pluck('akses')->toArray();
        
        $parents        = Modul::whereNull('parent_id')->get();
        $permissions    = Permissions::all();
        $icons          = Icon::all();
        
        return view($this->modul.'.edit', [
            'modul_data'    => $modul_data,
            'parents'       => $parents, 
            'icons'         => $icons, 
            'permissions'   => $permissions,
            'selectedAccess'=> $selectedAccess,
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'Edit'
        ]);
    }

    public function update(Request $request, Modul $modul)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name'      => 'required|string',
                'akses'     => 'array'
            ]);

            // 1. ambil data lama
            $old = $modul->toArray();

            // 2. Update Data
            $modul->update([
                'kode'      => $request->kode,
                'name'      => $request->name,
                'parent_id' => $request->parent_id,
                'icon'      => $request->icon,
                'shortcut'  => $request->shortcut
            ]);

            // 3. Hapus access lama
            $modul->modulAkses()->delete();

            // 4. Tambah access baru
            if ($request->akses) {
                foreach ($request->akses as $akses) {
                    ModulAkses::create([
                        'modul_id' => $modul->id,
                        'akses'    => $akses,
                    ]);
                }
            }

            // 5. Save Proses ke "activity_logs"
            ActivityLogger::log(
                $this->modul,
                'update',
                $modul->id,
                [
                    'before' => $old,
                    'after'  => $modul->fresh()->toArray()
                ],
                auth()->id()
            );

            DB::commit();

            return redirect()->route($this->modul.'.index')->with('success', 'Data has been updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();
            // Optionally, log the error
            return redirect()->route($this->modul.'.index')->with('error', 'Failed updated '.$this->modul.': '.$e->getMessage());
        
        }
    }

    public function destroy($id)
    {

        // DB::beginTransaction();
        // try {
            $modul = Modul::findOrFail($id);

            // Soft delete semua ModulAkses yang modul_id = $id (bukan hard delete)
            ModulAkses::where('modul_id', $modul->id)->delete();

            ActivityLogger::log(
                $this->modul,
                'delete',
                $modul->getKey(),
                [],
                auth()->id() // gunakan id user yang aktif
            );

            $modul->delete(); // soft delete modul

            return response()->json([
                'success' => true,
                'message' => 'Data has been deleted successfully.'
            ]);
        
        // } catch (\Exception $e) {

        //     DB::rollBack();
        //     // Optionally, log the error
        //     return redirect()->route($this->modul.'.index')->with('error', 'Failed to delete data. Please try again: '.$e->getMessage());
        
        // }
    }

    public function menus()
    {
        $menus = \App\Models\Modul::whereNull('parent_id')
                ->with('childrenRecursive')
                ->orderBy('sort_order')
                ->get();

    }

    public function getData(Request $request)
    {
        // $query = Modul::query();

        $query = DB::table('moduls as m')
            ->leftJoin('moduls as parent', 'parent.id', '=', 'm.parent_id')
            ->select([
                'm.*',
                DB::raw('COALESCE(parent.name, m.name) as parent_name')
            ])
            ->whereNull('m.deleted_at'); // Hide soft deleted data for moduls

        if ($request->filled('filter_code')) {
            $query->where('m.kode', 'like', "%{$request->filter_code}%");
        }
    
        if ($request->filled('filter_name')) {
            $query->where('m.name', 'like', "%{$request->filter_name}%");
        }
    
        if ($request->filled('filter_parent')) {
            $query->where(function($q) use ($request) {
                $q->where('m.parent_id', $request->filter_parent)
                  ->orWhere('m.id', $request->filter_parent);
            });
        }
            
        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('mobile_view', function ($row) {
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="fw-bold">'.e($row->name).'</div>
                        <a class="toggle-expand btn btn-xs btn-secondary">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta">
                            <span class="flex items-center mb-1"><i class="fa-solid fa-gear mr-2"></i><span class="fw-bold">'.e($row->parent_name).'</span></span>
                            <span class="flex items-center"><i class="fa-solid fa-user-tag mr-2"></i><span class="fw-bold">'.e($row->kode).'</span></span>
                            <br>
                            <div class="action-mobile">
                                ' . view('components.datatables.button-edit', [
                                    'id' => $row->id,
                                    'modul' => $this->modul,
                                ])->render() . '
                                ' . view('components.datatables.button-delete', [
                                    'id' => $row->id,
                                    'name' => $row->name,
                                ])->render() . '
                            </div>
                        </div>
                    </div>
                </div>
                ';
            })
            ->addColumn('action', function ($row) {
                
                $btn = "";

                if (canAccess($this->modul, $this->role_id, 'edit')) {

                    $btn .= view('components.datatables.button-edit', [
                        
                        'id' => $row->id,
                        'modul' => $this->modul,
                    
                        ])->render();

                }
                
                if (canAccess($this->modul, $this->role_id, 'delete')) {

                    $btn .= view('components.datatables.button-delete', [
                        'id'    => $row->id,
                        'name'  => $row->name,
                    ])->render();
                
                }

                return $btn;

            })->rawColumns(['name_package', 'status', 'action', 'mobile_view'])->make(true);
    }

    public function getDataRecycle(Request $request)
    {
        // $query = Modul::query();

        $query = DB::table('moduls as m')
            ->leftJoin('moduls as parent', 'parent.id', '=', 'm.parent_id')
            ->select([
                'm.*',
                DB::raw('COALESCE(parent.name, m.name) as parent_name')
            ])
            ->whereNotNull('m.deleted_at'); // ✅ ONLY SOFT DELETED


        if ($request->filled('filter_code')) {
            $query->where('m.kode', 'like', "%{$request->filter_code}%");
        }
    
        if ($request->filled('filter_name')) {
            // Combine name and parent.name filtering into a single "or" group, and also exclude soft deleted parent rows
            $query->where(function($q) use ($request) {
                $q->where('m.name', 'like', "%{$request->filter_name}%")
                  ->orWhere(function($q2) use ($request) {
                      $q2->where('parent.name', 'like', "%{$request->filter_name}%")
                         ->whereNotNull('m.deleted_at');
                  });
            });
        }
    
        if ($request->filled('filter_parent')) {
            $query->where(function($q) use ($request) {
                $q->where('m.parent_id', $request->filter_parent)
                  ->orWhere('m.id', $request->filter_parent);
            });
        }
            
        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('mobile_view', function ($row) {
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="fw-bold">'.e($row->name).'</div>
                        <a class="toggle-expand btn btn-xs btn-secondary">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta">
                            <span class="flex items-center mb-1"><i class="fa-solid fa-gear mr-2"></i><span class="fw-bold">'.e($row->parent_name).'</span></span>
                            <span class="flex items-center"><i class="fa-solid fa-user-tag mr-2"></i><span class="fw-bold">'.e($row->kode).'</span></span>
                            <br>
                            <div class="action-mobile">
                                '.view('components.datatables.button-restore', [
                                    'id' => $row->id,
                                    'name' => $row->name,
                                ])->render().'
                            </div>
                        </div>
                    </div>
                </div>
                ';
            })
            ->addColumn('action', function ($row) {
                
                $btn = "";
                // if(isAllowed(static::$modul, "edit"))://Check permission
                $btn .= view('components.datatables.button-restore', [
                    'id' => $row->id,
                    'name' => $row->name,
                ])->render();
                // endif;
                return $btn;
            })->rawColumns(['name_package', 'status', 'action', 'mobile_view'])->make(true);
    }

    public function restore($id){

        $dataAkses = ModulAkses::onlyTrashed()->where('modul_id', $id)->get();
        foreach ($dataAkses as $dataPermission) {
            $dataPermission->restore();
        }

        $data = Modul::onlyTrashed()->findOrFail($id);
        $data->restore();

        ActivityLogger::log(
            $this->modul,      // modul
            'restore',          // action
            $data->id,         // transaction_id
            ['name' => $data->name], // payload (WAJIB ARRAY)
            auth()->id()        // user_id
        );

        return response()->json([
            'status'  => true,
            'message' => 'Data has been restored successfully.'
        ]);
    }

    
}
