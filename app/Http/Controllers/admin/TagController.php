<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TagController extends Controller
{
    protected $modul        = "tags";
    protected $path         = "admin.tags";
    protected $modul_name   = "Tag";

    protected function getRoleId()
    {
        return auth()->user() ? (auth()->user()->role_id ?? auth()->user()->product_id) : 0;
    }

    public function index()
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'view') == false) {
            return redirect()->route('admin.dashboard');
        }

        return view($this->path . '.index', [
            'canAdd'        => canAccess($this->modul, $role_id, 'add'),
            'canEdit'       => canAccess($this->modul, $role_id, 'edit'),
            'canDelete'     => canAccess($this->modul, $role_id, 'delete'),
            'canDetail'     => canAccess($this->modul, $role_id, 'detail'),
            'canRecycle'    => canAccess($this->modul, $role_id, 'recycle'),
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'List'
        ]);
    }

    public function recycle()
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'recycle') == false) {
            if (canAccess($this->modul, $role_id, 'view') == true) {
                return redirect()->route('admin.' . $this->modul . '.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return view($this->path . '.recycle', [
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'Recycle'
        ]);
    }

    public function create()
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'add') == false) {
            if (canAccess($this->modul, $role_id, 'view') == true) {
                return redirect()->route('admin.' . $this->modul . '.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return view($this->path . '.create', [
            'types'         => Tag::TYPES,
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'Create'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', Tag::TYPES),
        ]);

        DB::beginTransaction();
        try {
            $tag = Tag::create([
                // Code is generated automatically (TAG-0001, TAG-0002, ...)
                'code' => Tag::generateCode(),
                'nama' => $request->nama,
                'type' => $request->type,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'create',
                $tag->id,
                ['code' => $tag->code, 'nama' => $tag->nama, 'type' => $tag->type],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul . '.index')->with('success', 'Tag created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul . '.index')->with('error', 'Failed to create data: ' . $e->getMessage());
        }
    }

    public function edit(Tag $tag)
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'edit') == false) {
            if (canAccess($this->modul, $role_id, 'view') == true) {
                return redirect()->route('admin.' . $this->modul . '.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return view($this->path . '.edit', [
            'tag'           => $tag,
            'types'         => Tag::TYPES,
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'Edit',
        ]);
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'type' => 'required|in:' . implode(',', Tag::TYPES),
        ]);

        DB::beginTransaction();
        try {
            // Code stays fixed once generated — only name & type are editable.
            $tag->update([
                'nama' => $request->nama,
                'type' => $request->type,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'update',
                $tag->id,
                ['code' => $tag->code, 'nama' => $tag->nama, 'type' => $tag->type],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul . '.index')->with('success', 'Tag updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul . '.index')->with('error', 'Failed to update data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);

        ActivityLogger::log(
            $this->modul,
            'delete',
            $tag->id,
            ['code' => $tag->code, 'nama' => $tag->nama],
            auth()->id()
        );

        $tag->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {
        $role_id = $this->getRoleId();
        $query = DB::table('tags')
            ->select(['tags.*'])
            ->whereNull('tags.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('tags.nama', 'like', "%{$request->filter_name}%")
                  ->orWhere('tags.code', 'like', "%{$request->filter_name}%");
            });
        }

        if ($request->filled('filter_type') && in_array($request->filter_type, Tag::TYPES)) {
            $query->where('tags.type', $request->filter_type);
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('type_view', function ($row) {
                $colors = [
                    'template' => 'bg-primary/10 text-primary',
                    'section'  => 'bg-info/10 text-info',
                    'other'    => 'bg-slate-200/60 text-slate-600 dark:bg-navy-450/20 dark:text-navy-100',
                ];

                $label = ucfirst($row->type);
                $color = $colors[$row->type] ?? $colors['other'];

                return '<span class="badge rounded-full px-2.5 py-1 text-xs font-medium ' . $color . '">' . e($label) . '</span>';
            })
            ->addColumn('mobile_view', function ($row) {
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="fw-bold text-slate-700 dark:text-navy-100">' . e($row->nama) . '</div>
                            <span class="text-xs text-slate-400">Code: ' . e($row->code) . '</span>
                        </div>
                        <a class="toggle-expand btn btn-xs btn-secondary">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <p class="text-xs text-slate-500 mb-2">Type: ' . e(ucfirst($row->type)) . '</p>
                        <div class="mobile-meta">
                            <div class="action-mobile">
                                ' . view('components.datatables.button-edit', [
                    'id' => $row->id,
                    'modul' => $this->modul,
                ])->render() . '
                                ' . view('components.datatables.button-delete', [
                    'id' => $row->id,
                    'name' => $row->nama,
                ])->render() . '
                            </div>
                        </div>
                    </div>
                </div>
                ';
            })
            ->addColumn('action', function ($row) use ($role_id) {
                $btn = "";
                if (canAccess($this->modul, $role_id, 'edit')) {
                    $btn .= view('components.datatables.button-edit', [
                        'id' => $row->id,
                        'modul' => $this->modul,
                    ])->render();
                }

                if (canAccess($this->modul, $role_id, 'delete')) {
                    $btn .= view('components.datatables.button-delete', [
                        'id' => $row->id,
                        'name' => $row->nama,
                    ])->render();
                }

                return $btn;
            })
            ->rawColumns(['type_view', 'action', 'mobile_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {
        $query = DB::table('tags')
            ->select(['tags.*'])
            ->whereNotNull('tags.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('tags.nama', 'like', "%{$request->filter_name}%")
                  ->orWhere('tags.code', 'like', "%{$request->filter_name}%");
            });
        }

        if ($request->filled('filter_type') && in_array($request->filter_type, Tag::TYPES)) {
            $query->where('tags.type', $request->filter_type);
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('type_view', function ($row) {
                return '<span class="badge rounded-full px-2.5 py-1 text-xs font-medium bg-slate-200/60 text-slate-600 dark:bg-navy-450/20 dark:text-navy-100">' . e(ucfirst($row->type)) . '</span>';
            })
            ->addColumn('mobile_view', function ($row) {
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="fw-bold">' . e($row->nama) . '</div>
                        <a class="toggle-expand btn btn-xs btn-secondary">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta">
                            <div class="action-mobile">
                                ' . view('components.datatables.button-restore', [
                    'id' => $row->id,
                    'name' => $row->nama,
                ])->render() . '
                            </div>
                        </div>
                    </div>
                </div>
                ';
            })
            ->addColumn('action', function ($row) {
                return view('components.datatables.button-restore', [
                    'id' => $row->id,
                    'name' => $row->nama,
                ])->render();
            })
            ->rawColumns(['type_view', 'action', 'mobile_view'])
            ->make(true);
    }

    public function restore($id)
    {
        $data = Tag::onlyTrashed()->findOrFail($id);
        $data->restore();

        ActivityLogger::log(
            $this->modul,
            'restore',
            $data->id,
            ['code' => $data->code, 'nama' => $data->nama],
            auth()->id()
        );

        return response()->json([
            'status' => true,
            'message' => 'Data has been restored successfully.'
        ]);
    }
}
