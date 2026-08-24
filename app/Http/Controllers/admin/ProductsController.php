<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
{
    protected $modul        = "products";
    protected $path         = "admin.products";
    protected $modul_name   = "Products";

    protected function getRoleId()
    {
        return auth()->user() ? (auth()->user()->role_id ?? auth()->user()->product_id) : 0;
    }

    /**
     * Generate unique random code for Product
     */
    private function generateUniqueCode()
    {
        do {
            $code = 'PROD-' . rand(10000, 99999);
        } while (Product::withTrashed()->where('code', $code)->exists());

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
                return redirect()->route('admin.' . $this->modul)->with('warning', 'Tidak Memiliki Akses');
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
                return redirect()->route('admin.' . $this->modul)->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        $categories = CategoryProduct::orderBy('name', 'asc')->get();
        $autoCode = $this->generateUniqueCode();

        return view($this->path . '.create', [
            'categories' => $categories,
            'autoCode'   => $autoCode,
            'modul'      => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Create'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_products_id' => 'required|exists:category_products,id',
            'code'                 => 'required|string|max:100|unique:products,code',
            'name'                 => 'required|string|max:255',
            'price'                => 'required|numeric|min:0',
            'description'          => 'nullable|string',
            'images'               => 'nullable|array',
            'images.*'             => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $imagePaths = [];
            if ($request->hasFile('images')) {
                if (!Storage::disk('public')->exists('products')) {
                    Storage::disk('public')->makeDirectory('products');
                }

                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('products', $filename, 'public');
                    $imagePaths[] = $path;
                }
            }

            $product = Product::create([
                'category_products_id' => $request->category_products_id,
                'code'                 => $request->code,
                'name'                 => $request->name,
                'price'                => $request->price,
                'description'          => $request->description,
                'images'               => $imagePaths,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'create',
                $product->id,
                ['name' => $product->name, 'code' => $product->code, 'price' => $product->price],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul)->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul)->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'edit') == false) {
            if (canAccess($this->modul, $role_id, 'view') == true) {
                return redirect()->route('admin.' . $this->modul)->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        $categories = CategoryProduct::orderBy('name', 'asc')->get();

        return view($this->path . '.edit', [
            'product'    => $product,
            'categories' => $categories,
            'modul'      => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Edit',
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_products_id' => 'required|exists:category_products,id',
            'code'                 => 'required|string|max:100|unique:products,code,' . $product->id,
            'name'                 => 'required|string|max:255',
            'price'                => 'required|numeric|min:0',
            'description'          => 'nullable|string',
            'images'               => 'nullable|array',
            'images.*'             => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'deleted_images'       => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $existingImages = $product->images ?? [];

            // Remove deleted images from disk & array
            if ($request->has('deleted_images') && is_array($request->deleted_images)) {
                foreach ($request->deleted_images as $deletedPath) {
                    if (($key = array_search($deletedPath, $existingImages)) !== false) {
                        if (Storage::disk('public')->exists($deletedPath)) {
                            Storage::disk('public')->delete($deletedPath);
                        }
                        unset($existingImages[$key]);
                    }
                }
                $existingImages = array_values($existingImages);
            }

            // Append new uploaded images
            if ($request->hasFile('images')) {
                if (!Storage::disk('public')->exists('products')) {
                    Storage::disk('public')->makeDirectory('products');
                }

                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('products', $filename, 'public');
                    $existingImages[] = $path;
                }
            }

            $product->update([
                'category_products_id' => $request->category_products_id,
                'code'                 => $request->code,
                'name'                 => $request->name,
                'price'                => $request->price,
                'description'          => $request->description,
                'images'               => $existingImages,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'update',
                $product->id,
                ['name' => $product->name, 'code' => $product->code, 'price' => $product->price],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul)->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul)->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        ActivityLogger::log(
            $this->modul,
            'delete',
            $product->id,
            ['name' => $product->name, 'code' => $product->code],
            auth()->id()
        );

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {
        $role_id = $this->getRoleId();
        $query = DB::table('products')
            ->leftJoin('category_products', 'category_products.id', '=', 'products.category_products_id')
            ->select([
                'products.*',
                'category_products.name as category_name'
            ])
            ->whereNull('products.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('products.name', 'like', "%{$request->filter_name}%")
                  ->orWhere('products.code', 'like', "%{$request->filter_name}%")
                  ->orWhere('category_products.name', 'like', "%{$request->filter_name}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('category_view', function ($row) {
                return e($row->category_name ?? '-');
            })
            ->addColumn('price_view', function ($row) {
                return 'Rp ' . number_format($row->price, 0, ',', '.');
            })
            ->addColumn('image_view', function ($row) {
                $images = json_decode($row->images, true);
                if (!empty($images) && is_array($images) && count($images) > 0) {
                    $firstImg = $images[0];
                    $url = asset('storage/' . $firstImg);
                    $countBadge = count($images) > 1 ? '<span class="absolute -top-1 -right-1 bg-primary text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow">' . count($images) . '</span>' : '';
                    return '<div class="relative inline-block"><img src="' . $url . '" alt="' . e($row->name) . '" class="h-12 w-12 object-cover rounded-lg shadow-sm border border-slate-200" />' . $countBadge . '</div>';
                }
                return '<div class="h-12 w-12 rounded-lg bg-slate-100 dark:bg-navy-600 flex items-center justify-center text-xs text-slate-400 font-medium">No Image</div>';
            })
            ->addColumn('mobile_view', function ($row) {
                $images = json_decode($row->images, true);
                $firstImg = (!empty($images) && is_array($images) && count($images) > 0) ? $images[0] : null;

                $imgHtml = $firstImg
                    ? '<img src="' . asset('storage/' . $firstImg) . '" alt="' . e($row->name) . '" class="h-10 w-10 object-cover rounded-lg mr-3 shadow-sm border border-slate-200" />'
                    : '<div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-xs text-slate-400 mr-3">No Img</div>';

                $catName = e($row->category_name ?? '-');
                $formattedPrice = 'Rp ' . number_format($row->price, 0, ',', '.');

                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            ' . $imgHtml . '
                            <div>
                                <div class="fw-bold text-slate-700 dark:text-navy-100">' . e($row->name) . '</div>
                                <span class="text-xs text-slate-400">' . e($row->code) . ' | Category: ' . $catName . '</span>
                                <div class="text-xs font-semibold text-success mt-0.5">' . $formattedPrice . '</div>
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
            ->rawColumns(['image_view', 'action', 'mobile_view', 'price_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {
        $query = DB::table('products')
            ->leftJoin('category_products', 'category_products.id', '=', 'products.category_products_id')
            ->select([
                'products.*',
                'category_products.name as category_name'
            ])
            ->whereNotNull('products.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('products.name', 'like', "%{$request->filter_name}%")
                  ->orWhere('products.code', 'like', "%{$request->filter_name}%")
                  ->orWhere('category_products.name', 'like', "%{$request->filter_name}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('category_view', function ($row) {
                return e($row->category_name ?? '-');
            })
            ->addColumn('price_view', function ($row) {
                return 'Rp ' . number_format($row->price, 0, ',', '.');
            })
            ->addColumn('image_view', function ($row) {
                $images = json_decode($row->images, true);
                if (!empty($images) && is_array($images) && count($images) > 0) {
                    $firstImg = $images[0];
                    $url = asset('storage/' . $firstImg);
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
            ->rawColumns(['image_view', 'action', 'mobile_view', 'price_view'])
            ->make(true);
    }

    public function restore($id)
    {
        $data = Product::onlyTrashed()->findOrFail($id);
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
            'message' => 'Product has been restored successfully.'
        ]);
    }
}
