<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Modul;
use App\Models\Permissions;
use App\Models\RolePermission;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
{
    /**
     * Display a listing of roles.
     */
    protected $modul = "products";
    protected $path = "products";
    protected $modul_name = "Products";
    protected $product_id;

    public function __construct()
    {
        $this->product_id = auth()->user()->product_id;
    }

    public function index()
    {
        if (canAccess($this->modul, $this->product_id, 'view') == false) {
            return redirect()->route('dashboard');
        }

        // $products = Product::latest()->paginate(10);

        // return view('admin.products.index', compact('products'), [
        return view('admin.products.index', [
            'canAdd' => canAccess($this->modul, $this->product_id, 'add'),
            'canEdit' => canAccess($this->modul, $this->product_id, 'edit'),
            'canDelete' => canAccess($this->modul, $this->product_id, 'delete'),
            'canDetail' => canAccess($this->modul, $this->product_id, 'detail'),
            'canRecycle' => canAccess($this->modul, $this->product_id, 'recycle'),
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'List'
        ]);
    }

    public function create()
    {
        // Group by module: product => [permissions]
        if (!canAccess($this->modul, $this->product_id, 'add')) {
            if (canAccess($this->modul, $this->product_id, 'view')) {
                // Fix: use explicit 'roles.index' route name to avoid RouteNotFoundException due to dynamic/non-existent route
                return redirect()->route($this->modul . '.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        $moduls = Modul::with('modulAkses')->get();

        return view(
            $this->modul . '.create',
            compact('moduls'),
            [
                'modul' => $this->modul,
                'modul_path' => $this->path,
                'modul_name' => $this->modul_name,
                'modul_type' => 'Create'
            ]
        );
    }

    /**
     * Store a new role.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'akses' => 'array'
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'name' => $request->name,
            ]);

            if ($request->has('akses') && is_array($request->akses)) {
                foreach ($request->akses as $akses) {
                    RolePermission::create([
                        'product_id' => $role->id,
                        'permission_id' => $role->id,
                        'modul_akses_id' => $akses,
                    ]);
                }
            }

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'create',
                $role->id,
                ['name' => $role->name],
                auth()->id()
            );

            return redirect()->route($this->modul . '.index')->with('success', 'Data has been created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route($this->modul . '.index')->with('error', 'Failed to create data. Please try again. : ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the role.
     */
    public function edit(Role $role)
    {
        if (canAccess($this->modul, $this->product_id, 'edit') == false) {
            if (canAccess($this->modul, $this->product_id, 'view') == true) {
                return redirect()->route($this->modul . '.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        $permissions = RolePermission::where('product_id', $role->id)
            ->pluck('modul_akses_id')
            ->toArray();

        $moduls = Modul::with('modulAkses')->get();

        return view($this->modul . '.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'moduls' => $moduls,
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Recycle',
        ]);
    }

    /**
     * Update the role.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string',
            'akses' => 'array'
        ]);

        $role->update([
            'name' => $request->name
        ]);

        // Hapus access lama
        $role->RolePermission()->delete();

        // Tambah access baru
        if ($request->akses) {
            foreach ($request->akses as $akses) {
                RolePermission::create([
                    'product_id' => $role->id,
                    'permission_id' => $role->id,
                    'modul_akses_id' => $akses,
                ]);
            }
        }

        return redirect()->route($this->modul . '.index')->with('success', 'Data updated successfully.');
    }

    /**
     * Remove the role.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        $role->RolePermission()->each(function ($permission) {
            $permission->delete();
        });

        ActivityLogger::log(
            $this->modul,
            'delete',
            $role->getKey(),
            [],
            auth()->user() ? auth()->user()->getKey() : null
        );

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {

        $query = DB::table('roles')->select(['roles.*'])
            ->whereNull('deleted_at'); // Hide soft deleted data for roles

        if ($request->filled('filter_name')) {

            $query->where(function ($q) use ($request) {
                $q->where('roles.name', 'like', "%{$request->filter_name}%");
            });

        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('mobile_view', function ($row) {
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="fw-bold">' . e($row->name) . '</div>
                        <a class="toggle-expand btn btn-xs btn-secondary">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta">
                            <div class="action-mobile">
                                ' . view('components.datatables.button-edit', [
                        'id' => $row->id,
                        'modul' => 'users',
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

                if (canAccess('roles', $this->product_id, 'edit')) {

                    $btn .= view('components.datatables.button-edit', [
                        'id' => $row->id,
                        'modul' => $this->modul,
                    ])->render();
                }

                if (canAccess('roles', $this->product_id, 'delete')) {

                    $btn .= view('components.datatables.button-delete', [
                        'id' => $row->id,
                        'name' => $row->name,
                    ])->render();

                }

                return $btn;
            })->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {
        $query = DB::table('roles')->select(['roles.*'])
            ->whereNotNull('deleted_at'); // Show soft deleted data for roles

        if ($request->filled('filter_name')) {

            $query->where(function ($q) use ($request) {
                $q->where('roles.name', 'like', "%{$request->filter_name}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('mobile_view', function ($row) {
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="fw-bold">' . e($row->name) . '</div>
                        <a class="toggle-expand btn btn-xs btn-secondary">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta">
                            <div class="action-mobile">
                                ' . view('components.datatables.button-restore', [
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

                $btn .= view('components.datatables.button-restore', [
                    'id' => $row->id,
                    'name' => $row->name,
                ])->render();

                return $btn;
            })->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function restore($id)
    {

        $dataPermissions = RolePermission::onlyTrashed()->where('product_id', $id)->get();
        foreach ($dataPermissions as $dataPermission) {
            $dataPermission->restore();
        }

        $data = Role::onlyTrashed()->findOrFail($id);
        $data->restore();

        ActivityLogger::log(
            $this->modul,      // modul
            'restore',          // action
            $data->id,         // transaction_id
            ['name' => $data->name], // payload (WAJIB ARRAY)
            auth()->id()        // user_id
        );

        return response()->json([
            'status' => true,
            'message' => 'Data has been restored successfully.'
        ]);
    }
}
