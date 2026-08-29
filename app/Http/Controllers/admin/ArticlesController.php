<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\CustomersWebsite;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ArticlesController extends Controller
{
    protected $modul        = "articles";
    protected $path         = "admin.articles";
    protected $modul_name   = "Articles";

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

        $customers_websites = CustomersWebsite::orderBy('title', 'asc')->get();
        $categories = collect();

        return view($this->path . '.create', [
            'customers_websites'  => $customers_websites,
            'categories' => $categories,
            'modul'      => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Create'
        ]);
    }

    public function getCategoriesByCustomer($customers_website_id = null)
    {
        if (!$customers_website_id) {
            return response()->json([]);
        }

        $categories = ArticleCategory::where('customers_website_id', $customers_website_id)
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customers_website_id'  => 'required|exists:customers_website,id',
            'article_categories_id' => 'required|exists:article_categories,id',
            'title'                 => 'required|string|max:255',
            'subtitle'              => 'nullable|string|max:255',
            'description'           => 'nullable|string',
            'author'                => 'nullable|string|max:255',
            'published_date'        => 'nullable|date',
            'images'                => 'nullable|array',
            'images.*'              => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $imagePaths = [];
            if ($request->hasFile('images')) {
                if (!Storage::disk('public')->exists('articles')) {
                    Storage::disk('public')->makeDirectory('articles');
                }

                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('articles', $filename, 'public');
                    $imagePaths[] = $path;
                }
            }

            $article = Article::create([
                'customers_website_id'  => $request->customers_website_id,
                'article_categories_id' => $request->article_categories_id,
                'title'                 => $request->title,
                'subtitle'              => $request->subtitle,
                'description'           => $request->description,
                'author'                => $request->author,
                'published_date'        => $request->published_date,
                'images'                => $imagePaths,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'create',
                $article->id,
                ['title' => $article->title, 'customers_website_id' => $article->customers_website_id],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul)->with('success', 'Article created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul)->with('error', 'Failed to create article: ' . $e->getMessage());
        }
    }

    public function edit(Article $article)
    {
        $role_id = $this->getRoleId();
        if (canAccess($this->modul, $role_id, 'edit') == false) {
            if (canAccess($this->modul, $role_id, 'view') == true) {
                return redirect()->route('admin.' . $this->modul)->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        $customers_websites = CustomersWebsite::orderBy('title', 'asc')->get();
        $selectedCustomersWebsiteId = old('customers_website_id', $article->customers_website_id);
        $categories = $selectedCustomersWebsiteId 
            ? ArticleCategory::where('customers_website_id', $selectedCustomersWebsiteId)->orderBy('name', 'asc')->get()
            : ArticleCategory::orderBy('name', 'asc')->get();

        return view($this->path . '.edit', [
            'article'    => $article,
            'customers_websites'  => $customers_websites,
            'categories' => $categories,
            'modul'      => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Edit',
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'customers_website_id'  => 'required|exists:customers_website,id',
            'article_categories_id' => 'required|exists:article_categories,id',
            'title'                 => 'required|string|max:255',
            'subtitle'              => 'nullable|string|max:255',
            'description'           => 'nullable|string',
            'author'                => 'nullable|string|max:255',
            'published_date'        => 'nullable|date',
            'images'                => 'nullable|array',
            'images.*'              => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'deleted_images'        => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $existingImages = $article->images ?? [];

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
                if (!Storage::disk('public')->exists('articles')) {
                    Storage::disk('public')->makeDirectory('articles');
                }

                foreach ($request->file('images') as $file) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('articles', $filename, 'public');
                    $existingImages[] = $path;
                }
            }

            $article->update([
                'customers_website_id'  => $request->customers_website_id,
                'article_categories_id' => $request->article_categories_id,
                'title'                 => $request->title,
                'subtitle'              => $request->subtitle,
                'description'           => $request->description,
                'author'                => $request->author,
                'published_date'        => $request->published_date,
                'images'                => $existingImages,
            ]);

            DB::commit();

            ActivityLogger::log(
                $this->modul,
                'update',
                $article->id,
                ['title' => $article->title, 'customers_website_id' => $article->customers_website_id],
                auth()->id()
            );

            return redirect()->route('admin.' . $this->modul)->with('success', 'Article updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.' . $this->modul)->with('error', 'Failed to update article: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        ActivityLogger::log(
            $this->modul,
            'delete',
            $article->id,
            ['title' => $article->title],
            auth()->id()
        );

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {
        $role_id = $this->getRoleId();
        $query = DB::table('articles')
            ->leftJoin('article_categories', 'article_categories.id', '=', 'articles.article_categories_id')
            ->leftJoin('customers_website', 'customers_website.id', '=', 'articles.customers_website_id')
            ->select([
                'articles.*',
                'article_categories.name as category_name',
                'customers_website.title as customer_name'
            ])
            ->whereNull('articles.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('articles.title', 'like', "%{$request->filter_name}%")
                  ->orWhere('articles.subtitle', 'like', "%{$request->filter_name}%")
                  ->orWhere('articles.author', 'like', "%{$request->filter_name}%")
                  ->orWhere('article_categories.name', 'like', "%{$request->filter_name}%")
                  ->orWhere('customers_website.title', 'like', "%{$request->filter_name}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('customer_view', function ($row) {
                return e($row->customer_name ?? '-');
            })
            ->addColumn('category_view', function ($row) {
                return e($row->category_name ?? '-');
            })
            ->addColumn('published_date_view', function ($row) {
                return $row->published_date ? date('d M Y', strtotime($row->published_date)) : '-';
            })
            ->addColumn('image_view', function ($row) {
                $images = json_decode($row->images, true);
                if (!empty($images) && is_array($images) && count($images) > 0) {
                    $firstImg = $images[0];
                    $url = asset('storage/' . $firstImg);
                    $countBadge = count($images) > 1 ? '<span class="absolute -top-1 -right-1 bg-primary text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow">' . count($images) . '</span>' : '';
                    return '<div class="relative inline-block"><img src="' . $url . '" alt="' . e($row->title) . '" class="h-12 w-12 object-cover rounded-lg shadow-sm border border-slate-200" />' . $countBadge . '</div>';
                }
                return '<div class="h-12 w-12 rounded-lg bg-slate-100 dark:bg-navy-600 flex items-center justify-center text-xs text-slate-400 font-medium">No Image</div>';
            })
            ->addColumn('mobile_view', function ($row) {
                $images = json_decode($row->images, true);
                $firstImg = (!empty($images) && is_array($images) && count($images) > 0) ? $images[0] : null;

                $imgHtml = $firstImg
                    ? '<img src="' . asset('storage/' . $firstImg) . '" alt="' . e($row->title) . '" class="h-10 w-10 object-cover rounded-lg mr-3 shadow-sm border border-slate-200" />'
                    : '<div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-xs text-slate-400 mr-3">No Img</div>';

                $catName = e($row->category_name ?? '-');
                $custName = e($row->customer_name ?? '-');
                $pubDate = $row->published_date ? date('d M Y', strtotime($row->published_date)) : '-';

                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            ' . $imgHtml . '
                            <div>
                                <div class="fw-bold text-slate-700 dark:text-navy-100">' . e($row->title) . '</div>
                                <span class="text-xs text-slate-400">Category: ' . $catName . ' | Customer: ' . $custName . '</span>
                                <div class="text-xs font-semibold text-slate-500 mt-0.5">Author: ' . e($row->author ?? '-') . ' (' . $pubDate . ')</div>
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
                    'name' => $row->title,
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
                        'name' => $row->title,
                    ])->render();
                }

                return $btn;
            })
            ->rawColumns(['image_view', 'action', 'mobile_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {
        $query = DB::table('articles')
            ->leftJoin('article_categories', 'article_categories.id', '=', 'articles.article_categories_id')
            ->leftJoin('customers_website', 'customers_website.id', '=', 'articles.customers_website_id')
            ->select([
                'articles.*',
                'article_categories.name as category_name',
                'customers_website.title as customer_name'
            ])
            ->whereNotNull('articles.deleted_at');

        if ($request->filled('filter_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('articles.title', 'like', "%{$request->filter_name}%")
                  ->orWhere('articles.subtitle', 'like', "%{$request->filter_name}%")
                  ->orWhere('articles.author', 'like', "%{$request->filter_name}%")
                  ->orWhere('article_categories.name', 'like', "%{$request->filter_name}%")
                  ->orWhere('customers_website.title', 'like', "%{$request->filter_name}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('customer_view', function ($row) {
                return e($row->customer_name ?? '-');
            })
            ->addColumn('category_view', function ($row) {
                return e($row->category_name ?? '-');
            })
            ->addColumn('published_date_view', function ($row) {
                return $row->published_date ? date('d M Y', strtotime($row->published_date)) : '-';
            })
            ->addColumn('image_view', function ($row) {
                $images = json_decode($row->images, true);
                if (!empty($images) && is_array($images) && count($images) > 0) {
                    $firstImg = $images[0];
                    $url = asset('storage/' . $firstImg);
                    return '<img src="' . $url . '" alt="' . e($row->title) . '" class="h-12 w-12 object-cover rounded-lg shadow-sm border border-slate-200" />';
                }
                return '<div class="h-12 w-12 rounded-lg bg-slate-100 dark:bg-navy-600 flex items-center justify-center text-xs text-slate-400 font-medium">No Image</div>';
            })
            ->addColumn('mobile_view', function ($row) {
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between">
                        <div class="fw-bold">' . e($row->title) . '</div>
                        <a class="toggle-expand btn btn-xs btn-secondary">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta">
                            <div class="action-mobile">
                                ' . view('components.datatables.button-restore', [
                    'id' => $row->id,
                    'name' => $row->title,
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
                    'name' => $row->title,
                ])->render();
            })
            ->rawColumns(['image_view', 'action', 'mobile_view'])
            ->make(true);
    }

    public function restore($id)
    {
        $data = Article::onlyTrashed()->findOrFail($id);
        $data->restore();

        ActivityLogger::log(
            $this->modul,
            'restore',
            $data->id,
            ['title' => $data->title],
            auth()->id()
        );

        return response()->json([
            'status' => true,
            'message' => 'Article has been restored successfully.'
        ]);
    }
}
