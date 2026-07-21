<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class DepartemenController extends Controller
{
    /**
     * Display a listing of departemen.
     */
    protected $modul        = "departemen";
    protected $path         = "departemen";
    protected $modul_name   = "Departemen";
    protected $departemen_id;

    public function __construct()
    {
        $this->departemen_id = auth()->user()->departemen_id;
    }

    public function index()
    {

        if (canAccess($this->modul, $this->departemen_id, 'view') == false) {
            return redirect()->route('dashboard');
        }

        $departemen = Departemen::latest()->paginate(10);
   
        return view('departemen.index', compact('departemen'), [
            'canAdd'        => canAccess($this->modul, $this->departemen_id, 'add'),
            'canEdit'       => canAccess($this->modul, $this->departemen_id, 'edit'),
            'canDelete'     => canAccess($this->modul, $this->departemen_id, 'delete'),
            'canDetail'     => canAccess($this->modul, $this->departemen_id, 'detail'),
            'canRecycle'    => canAccess($this->modul, $this->departemen_id, 'recycle'),
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'List'
        ]);

    }

    public function recycle()
    {
        if (canAccess($this->modul, $this->departemen_id, 'recycle') == false) {
            if (canAccess($this->modul, $this->departemen_id, 'view') == true) {
                return redirect()->route($this->modul.'.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return view($this->modul.'.recycle',
            [   
                'modul'         => $this->modul,
                'modul_path'    => $this->path,
                'modul_name'    => $this->modul_name,
                'modul_type'    => 'Recycle'
            ]);
        
    }

    /**
     * Show the form for creating a new Departemen.
     */
    public function create()
    {
        // Group by module: product => [permissions]
        if (canAccess($this->modul, $this->departemen_id, 'add') == false) {
            if (canAccess($this->modul, $this->departemen_id, 'view') == true) {
                return redirect()->route($this->modul.'index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return view($this->modul.'.create',
            [
                'modul'         => $this->modul,
                'modul_path'    => $this->path,
                'modul_name'    => $this->modul_name,
                'modul_type'    => 'Create'
            ]
        );
    }

    /**
     * Store a new Departemen.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string',
            'akses' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $departemen = Departemen::create([
                'name' => $request->name,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'create',
                $departemen->id,
                ['name' => $departemen->name],
                auth()->id()
            );

            return redirect()->route($this->modul.'.index')->with('Data has been created successfully.');
    
        } catch (\Exception $e) {
            DB::rollBack();

            // Optionally log the error here, e.g. Log::error($e);

            return redirect()->route($this->modul.'.index')->with('error', 'Failed to create data. Please try again.
             : '.$e->getMessage());
        }
    }
    /**
     * Show the form for editing the Departemen.
     */
    public function edit(Departemen $departemen)
    {
        if (canAccess($this->modul, $this->departemen_id, 'edit') == false) {
            if (canAccess($this->modul, $this->departemen_id, 'view') == true) {
                return redirect()->route($this->modul.'.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }


        return view($this->modul.'.edit', compact('departemen'),
        [
            'modul'       => $this->modul,
            'modul_path'  => $this->path,
            'modul_name'  => $this->modul_name,
            'modul_type'  => 'Recycle',
        ]);
    }

    /**
     * Update the Departemen.
     */
    public function update(Request $request, Departemen $departemen)
    {
        $request->validate([
            'name'      => 'required|string'
        ]);

        $departemen->update([
            'name' => $request->name
        ]);

        return redirect()->route($this->modul.'.index')->with('success', 'Data updated successfully.');
    }

    /**
     * Remove the Departemen.
     */
    public function destroy($id)
    {
        $departemen = Departemen::findOrFail($id);

        ActivityLogger::log(
            $this->modul,
            'delete',
            $departemen->getKey(),
            [],
            auth()->user() ? auth()->user()->getKey() : null
        );

        $departemen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {

        $query = DB::table('departemens')->select(['departemens.*'])
                    ->whereNull('deleted_at'); // Hide soft deleted data for departemen

        if ($request->filled('filter_name')) {
    
            $query->where(function($q) use ($request) {
                $q->where('departemens.name', 'like', "%{$request->filter_name}%");
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

                if (canAccess($this->modul, $this->departemen_id, 'edit')) {

                    $btn .= view('components.datatables.button-edit', [
                        
                        'id' => $row->id,
                        'modul' => $this->modul,
                    
                        ])->render();

                }
                
                if (canAccess($this->modul, $this->departemen_id, 'delete')) {

                    $btn .= view('components.datatables.button-delete', [
                        'id'    => $row->id,
                        'name'  => $row->name,
                    ])->render();
                
                }

                return $btn;
            })->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {

        $query = DB::table('departemens')->select(['departemens.*'])
                    ->whereNotNull('deleted_at'); // Show soft deleted data for departemen

        if ($request->filled('filter_name')) {
            
            $query->where(function($q) use ($request) {
                $q->where('departemens.name', 'like', "%{$request->filter_name}%");
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

                $btn .= view('components.datatables.button-restore', [
                    'id' => $row->id,
                    'name' => $row->name,
                ])->render();

                return $btn;
            })->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function restore($id){

        $data = Departemen::onlyTrashed()->findOrFail($id);
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
