<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use App\Models\CustomersWebsite;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ArticleCategoryController extends Controller
{
    protected $modul        = "article-category";
    protected $path         = "admin.article-category";
    protected $modul_name   = "Article Category";

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

        $customers_websites = CustomersWebsite::orderBy('title', 'asc')->get();

        return view($this->path . '.create', [
            'customers_websites'     => $customers_websites,
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'Create'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customers_website_id' => 'required|exists:customers_website,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $articleCategory = ArticleCategory::create([
                'customers_website_id' => $request->customers_website_id,
                'name'         => $request->name,
                'description'  => $request->description,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'create',
                $articleCategory->id,
                ['name' => $articleCategory->name, 'customers_website_id' => $articleCategory->customers_website_id],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul . '.index')->with('success', 'Article Category created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul . '.index')->with('error', 'Failed to create data: ' . $e->getMessage());
        }
    }

    public function edit(ArticleCategory $article_category)
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'edit') == false) {
            if (canAccess($this->modul, $role_id, 'view') == true) {
                return redirect()->route('admin.' . $this->modul . '.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        $customers_websites = CustomersWebsite::orderBy('title', 'asc')->get();

        return view($this->path . '.edit', [
            'article_category' => $article_category,
            'customers_websites'        => $customers_websites,
            'modul'            => $this->modul,
            'modul_path'       => $this->path,
            'modul_name'       => $this->modul_name,
            'modul_type'       => 'Edit',
        ]);
    }

    public function update(Request $request, ArticleCategory $article_category)
    {
        $request->validate([
            'customers_website_id' => 'required|exists:customers_website,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $article_category->update([
                'customers_website_id' => $request->customers_website_id,
                'name'         => $request->name,
                'description'  => $request->description,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'update',
                $article_category->id,
                ['name' => $article_category->name, 'customers_website_id' => $article_category->customers_website_id],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul . '.index')->with('success', 'Article Category updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul . '.index')->with('error', 'Failed to update data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $articleCategory = ArticleCategory::findOrFail($id);

        ActivityLogger::log(
            $this->modul,
            'delete',
            $articleCategory->id,
            ['name' => $articleCategory->name],
            auth()->id()
        );

        $articleCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {
        $role_id = $this->getRoleId();
        $query = DB::table('article_categories')
            ->leftJoin('customers_website', 'customers_website.id', '=', 'article_categories.customers_website_id')
            ->select([
                'article_categories.*',
                'customers_website.title as customer_name'
            ])
            ->whereNull('article_categories.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('article_categories.name', 'like', "%{$request->filter_name}%")
                  ->orWhere('customers_website.title', 'like', "%{$request->filter_name}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('customer_view', function ($row) {
                return e($row->customer_name ?? '-');
            })
            ->addColumn('mobile_view', function ($row) {
                $custName = e($row->customer_name ?? '-');

                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="fw-bold text-slate-700 dark:text-navy-100">' . e($row->name) . '</div>
                            <span class="text-xs text-slate-400">Customer: ' . $custName . '</span>
                        </div>
                        <a class="toggle-expand btn btn-xs btn-secondary">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <p class="text-xs text-slate-500 mb-2">' . e($row->description ?? '-') . '</p>
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
        $query = DB::table('article_categories')
            ->leftJoin('customers_website', 'customers_website.id', '=', 'article_categories.customers_website_id')
            ->select([
                'article_categories.*',
                'customers_website.title as customer_name'
            ])
            ->whereNotNull('article_categories.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('article_categories.name', 'like', "%{$request->filter_name}%")
                  ->orWhere('customers_website.title', 'like', "%{$request->filter_name}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('customer_view', function ($row) {
                return e($row->customer_name ?? '-');
            })
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
                return view('components.datatables.button-restore', [
                    'id' => $row->id,
                    'name' => $row->name,
                ])->render();
            })
            ->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function restore($id)
    {
        $data = ArticleCategory::onlyTrashed()->findOrFail($id);
        $data->restore();

        ActivityLogger::log(
            $this->modul,
            'restore',
            $data->id,
            ['name' => $data->name],
            auth()->id()
        );

        return response()->json([
            'status' => true,
            'message' => 'Data has been restored successfully.'
        ]);
    }
}
