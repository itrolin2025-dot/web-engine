<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActivityLogger;


class UserController extends Controller
{
    protected $modul = "users";
    protected $path = "admin.users";
    protected $modul_name = "Users";
    protected $role_id;

    public function __construct()
    {
        $this->role_id = auth()->user()->role_id;
    }

    public function index()
    {

        if (canAccess($this->modul, $this->role_id, 'view') == false) {
            return redirect()->route('admin.dashboard');
        }

        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'), [
            'roles' => Role::whereNull('deleted_at')->get(),
            'canAdd' => canAccess($this->modul, $this->role_id, 'add'),
            'canEdit' => canAccess($this->modul, $this->role_id, 'edit'),
            'canDelete' => canAccess($this->modul, $this->role_id, 'delete'),
            'canDetail' => canAccess($this->modul, $this->role_id, 'detail'),
            'canRecycle' => canAccess($this->modul, $this->role_id, 'recycle'),
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'List'
        ]);

    }

    public function recycle()
    {
        if (canAccess($this->modul, $this->role_id, 'recycle') == false) {
            if (canAccess($this->modul, $this->role_id, 'view') == true) {
                return redirect()->route($this->modul . '.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        return view(
            $this->modul . '.recycle',
            [
                'roles' => Role::whereNull('deleted_at')->get(),
                'modul' => $this->modul,
                'modul_path' => $this->path,
                'modul_name' => $this->modul_name,
                'modul_type' => 'Recycle'
            ]
        );

    }

    public function create()
    {

        if (canAccess($this->modul, $this->role_id, 'add') == false) {
            if (canAccess($this->modul, $this->role_id, 'view') == true) {
                return redirect()->route('admin.users.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.users.create', [
            'roles' => Role::whereNull('deleted_at')->get(),
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Create'
        ]);
    }


    public function store(StoreUserRequest $request)
    {

        DB::beginTransaction();

        try {
            // 1. Insert Modul
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => Hash::make($request->password),
            ]);

            // 2. Save Proses ke "activity_logs"
            ActivityLogger::log(
                $this->modul,
                'create',
                $user->id,
                ['name' => $user->name],
                auth()->id()
            );

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Data has been created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // optionally log error here

            return redirect()->route($this->modul . '.index')->with('error', 'Failed to create data. Please try again.
             : ' . $e->getMessage());
        }

    }


    public function edit(User $user)
    {

        if (canAccess($this->modul, $this->role_id, 'edit') == false) {
            if (canAccess($this->modul, $this->role_id, 'view') == true) {
                return redirect()->route('admin.users.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return view('admin.users.edit', compact('user'), [
            'roles' => Role::whereNull('deleted_at')->get(),
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Edit'
        ]);
    }


    public function update(UpdateUserRequest $request, User $user)
    {

        DB::beginTransaction();

        try {
            $data = $request->only(['name', 'email', 'role_id']);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            ActivityLogger::log(
                $this->modul,
                'update',
                $user->getKey(),
                ['name' => $user->name],
                auth()->id()
            );

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Data updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // optionally log error here

            return redirect()->route('admin.users.index')->with('failed', 'Data not updated');
        }
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        ActivityLogger::log(
            $this->modul,
            'delete',
            $user->getKey(),
            [],
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Data has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {

        $query = DB::table('users')
            ->leftjoin('roles', 'users.role_id', '=', 'roles.id')
            ->select([
                'users.*',
                DB::raw("CASE WHEN users.role_id = 0 THEN 'Super Admin' ELSE roles.name END as role_name"),
            ])
            ->whereNull('users.deleted_at');

        if ($request->filled('filter_role')) {

            $query->where(function ($q) use ($request) {
                $q->where('users.role_id', $request->filter_role);
            });

        }

        if ($request->filled('filter_name')) {

            $query->where(function ($q) use ($request) {
                $q->where('users.name', 'like', "%{$request->filter_name}%");
            });

        }

        if ($request->filled('filter_username')) {

            $query->where(function ($q) use ($request) {
                $q->where('users.email', 'like', "%{$request->filter_username}%");
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
                            <span class="flex items-center mb-1"><i class="fa-solid fa-envelope mr-2"></i><span class="fw-bold">' . e($row->email) . '</span></span>
                            <span class="flex items-center"><i class="fa-solid fa-user-tag mr-2"></i><span class="fw-bold">' . e($row->role_name) . '</span></span>
                            <br>
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

                if (canAccess($this->modul, $this->role_id, 'edit')) {

                    $btn .= view('components.datatables.button-edit', [

                        'id' => $row->id,
                        'modul' => $this->modul,

                    ])->render();

                }

                if (canAccess($this->modul, $this->role_id, 'delete')) {

                    $btn .= view('components.datatables.button-delete', [
                        'id' => $row->id,
                        'name' => $row->name,
                    ])->render();

                }

                return $btn;
            })
            ->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {
        // $query = Modul::query();

        $query = DB::table('users')
            ->leftjoin('roles', 'users.role_id', '=', 'roles.id')
            ->select([
                'users.*',
                DB::raw("CASE WHEN users.role_id = 0 THEN 'Super Admin' ELSE roles.name END as role_name"),
            ])
            ->whereNotNull('users.deleted_at');

        if ($request->filled('filter_role')) {

            $query->where(function ($q) use ($request) {
                $q->where('users.role_id', $request->filter_role);
            });

        }

        if ($request->filled('filter_name')) {

            $query->where(function ($q) use ($request) {
                $q->where('users.name', 'like', "%{$request->filter_name}%");
            });

        }

        if ($request->filled('filter_username')) {

            $query->where(function ($q) use ($request) {
                $q->where('users.email', 'like', "%{$request->filter_username}%");
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
                            <span class="flex items-center mb-1"><i class="fa-solid fa-envelope mr-2"></i><span class="fw-bold">' . e($row->email) . '</span></span>
                            <span class="flex items-center"><i class="fa-solid fa-user-tag mr-2"></i><span class="fw-bold">' . e($row->role_name) . '</span></span>
                            <br>
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

                if (canAccess($this->modul, $this->role_id, 'recycle')) {

                    $btn = "";
                    // if(isAllowed(static::$modul, "edit"))://Check permission
                    $btn .= view('components.datatables.button-restore', [
                        'id' => $row->id,
                        'name' => $row->name,
                    ])->render();


                    // endif;
                    return $btn;

                }

            })
            ->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function restore($id)
    {

        $data = User::onlyTrashed()->findOrFail($id);
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
