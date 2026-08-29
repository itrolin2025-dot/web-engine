<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryProduct;
use App\Models\CustomersWebsite;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CategoryProductController extends Controller
{
    protected $modul        = "category-product";
    protected $path         = "admin.category-product";
    protected $modul_name   = "Category Product";

    protected function getRoleId()
    {
        return auth()->user() ? (auth()->user()->role_id ?? auth()->user()->product_id) : 0;
    }

    /**
     * Generate unique random code for Category Product
     */
    private function generateUniqueCode()
    {
        do {
            $code = 'CAT-' . rand(10000, 99999);
        } while (CategoryProduct::withTrashed()->where('code', $code)->exists());

        return $code;
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
        $autoCode = $this->generateUniqueCode();

        return view($this->path . '.create', [
            'customers_websites'     => $customers_websites,
            'autoCode'      => $autoCode,
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
            'code'        => 'required|string|max:100|unique:category_products,code',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                // Ensure target directory exists on disk 'public'
                if (!Storage::disk('public')->exists('category_products')) {
                    Storage::disk('public')->makeDirectory('category_products');
                }

                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('category_products', $filename, 'public');
            }

            $categoryProduct = CategoryProduct::create([
                'customers_website_id' => $request->customers_website_id,
                'code'         => $request->code,
                'name'         => $request->name,
                'description'  => $request->description,
                'image'        => $imagePath,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'create',
                $categoryProduct->id,
                ['name' => $categoryProduct->name, 'code' => $categoryProduct->code, 'customers_website_id' => $categoryProduct->customers_website_id],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul . '.index')->with('success', 'Category Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul . '.index')->with('error', 'Failed to create data: ' . $e->getMessage());
        }
    }

    public function edit(CategoryProduct $category_product)
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
            'category_product' => $category_product,
            'customers_websites'        => $customers_websites,
            'modul'            => $this->modul,
            'modul_path'       => $this->path,
            'modul_name'       => $this->modul_name,
            'modul_type'       => 'Edit',
        ]);
    }

    public function update(Request $request, CategoryProduct $category_product)
    {
        $request->validate([
            'customers_website_id' => 'required|exists:customers_website,id',
            'code'        => 'required|string|max:100|unique:category_products,code,' . $category_product->id,
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $imagePath = $category_product->image;
            if ($request->hasFile('image')) {
                // Ensure target directory exists
                if (!Storage::disk('public')->exists('category_products')) {
                    Storage::disk('public')->makeDirectory('category_products');
                }

                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $imagePath = $file->storeAs('category_products', $filename, 'public');
            }

            $category_product->update([
                'customers_website_id' => $request->customers_website_id,
                'code'         => $request->code,
                'name'         => $request->name,
                'description'  => $request->description,
                'image'        => $imagePath,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'update',
                $category_product->id,
                ['name' => $category_product->name, 'code' => $category_product->code, 'customers_website_id' => $category_product->customers_website_id],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul . '.index')->with('success', 'Category Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul . '.index')->with('error', 'Failed to update data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $categoryProduct = CategoryProduct::findOrFail($id);

        ActivityLogger::log(
            $this->modul,
            'delete',
            $categoryProduct->id,
            ['name' => $categoryProduct->name, 'code' => $categoryProduct->code],
            auth()->id()
        );

        $categoryProduct->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {
        $role_id = $this->getRoleId();
        $query = DB::table('category_products')
            ->leftJoin('customers_website', 'customers_website.id', '=', 'category_products.customers_website_id')
            ->select([
                'category_products.*',
                'customers_website.title as customer_name'
            ])
            ->whereNull('category_products.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('category_products.name', 'like', "%{$request->filter_name}%")
                  ->orWhere('category_products.code', 'like', "%{$request->filter_name}%")
                  ->orWhere('customers_website.title', 'like', "%{$request->filter_name}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('customer_view', function ($row) {
                return e($row->customer_name ?? '-');
            })
            ->addColumn('image_view', function ($row) {
                if (!empty($row->image)) {
                    $url = asset('storage/' . $row->image);
                    return '<img src="' . $url . '" alt="' . e($row->name) . '" class="h-12 w-12 object-cover rounded-lg shadow-sm border border-slate-200" />';
                }
                return '<div class="h-12 w-12 rounded-lg bg-slate-100 dark:bg-navy-600 flex items-center justify-center text-xs text-slate-400 font-medium">No Image</div>';
            })
            ->addColumn('mobile_view', function ($row) {
                $img = !empty($row->image)
                    ? '<img src="' . asset('storage/' . $row->image) . '" alt="' . e($row->name) . '" class="h-10 w-10 object-cover rounded-lg mr-3 shadow-sm border border-slate-200" />'
                    : '<div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-xs text-slate-400 mr-3">No Img</div>';

                $custName = e($row->customer_name ?? '-');

                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            ' . $img . '
                            <div>
                                <div class="fw-bold text-slate-700 dark:text-navy-100">' . e($row->name) . '</div>
                                <span class="text-xs text-slate-400">' . e($row->code) . ' | Customer: ' . $custName . '</span>
                            </div>
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
            ->rawColumns(['image_view', 'action', 'mobile_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {
        $query = DB::table('category_products')
            ->leftJoin('customers_website', 'customers_website.id', '=', 'category_products.customers_website_id')
            ->select([
                'category_products.*',
                'customers_website.title as customer_name'
            ])
            ->whereNotNull('category_products.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('category_products.name', 'like', "%{$request->filter_name}%")
                  ->orWhere('category_products.code', 'like', "%{$request->filter_name}%")
                  ->orWhere('customers_website.title', 'like', "%{$request->filter_name}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('customer_view', function ($row) {
                return e($row->customer_name ?? '-');
            })
            ->addColumn('image_view', function ($row) {
                if ($row->image && Storage::disk('public')->exists($row->image)) {
                    $url = asset('storage/' . $row->image);
                    return '<img src="' . $url . '" alt="' . e($row->name) . '" class="h-12 w-12 object-cover rounded-lg shadow-sm border border-slate-200" />';
                }
                return '<div class="h-12 w-12 rounded-lg bg-slate-100 dark:bg-navy-600 flex items-center justify-center text-xs text-slate-400 font-medium">No Image</div>';
            })
            ->addColumn('mobile_view', function ($row) {
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="fw-bold">' . e($row->name) . ' (' . e($row->code) . ')</div>
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
            ->rawColumns(['image_view', 'action', 'mobile_view'])
            ->make(true);
    }

    public function restore($id)
    {
        $data = CategoryProduct::onlyTrashed()->findOrFail($id);
        $data->restore();

        ActivityLogger::log(
            $this->modul,
            'restore',
            $data->id,
            ['name' => $data->name, 'code' => $data->code],
            auth()->id()
        );

        return response()->json([
            'status' => true,
            'message' => 'Data has been restored successfully.'
        ]);
    }
}
