<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomersWebsite;
use App\Models\CustomersWebsiteIdentity;
use App\Models\CustomersWebsiteLayout;
use App\Models\Template;
use App\Models\TemplatesSection;
use App\Models\TemplatesSectionContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CustomersWebController extends Controller
{
    protected $modul = "customers-website";
    protected $path = "customers-website";
    protected $modul_name = "Customers Website";

    protected function getProductId()
    {
        return auth()->check() ? auth()->user()->product_id : null;
    }

    public function index()
    {
        if (canAccess('customers', $this->getProductId(), 'view') == false) {
            return redirect()->route('admin.dashboard');
        }

        $websites = CustomersWebsite::with(['customer', 'template'])->orderBy('id', 'desc')->get();

        return view('admin.customers-website.index', [
            'websites' => $websites,
            'canAdd' => canAccess('customers', $this->getProductId(), 'add'),
            'canEdit' => canAccess('customers', $this->getProductId(), 'edit'),
            'canDelete' => canAccess('customers', $this->getProductId(), 'delete'),
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'List'
        ]);
    }

    public function getData(Request $request)
    {
        $query = DB::table('customers_website')
            ->leftJoin('customers', 'customers.id', '=', 'customers_website.customer_id')
            ->leftJoin('template', 'template.id', '=', 'customers_website.template_id')
            ->select([
                'customers_website.*',
                'customers.name as customer_name',
                'template.name as template_name',
            ]);

        if ($request->filled('filter_title')) {
            $query->where(function ($q) use ($request) {
                $q->where('customers_website.title', 'like', "%{$request->filter_title}%")
                  ->orWhere('customers_website.domain', 'like', "%{$request->filter_title}%")
                  ->orWhere('customers.name', 'like', "%{$request->filter_title}%");
            });
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('title_view', function ($row) {
                $cust = '<p class="text-xs text-slate-400 dark:text-navy-300 truncate max-w-xs">' . e($row->customer_name ?? '-') . '</p>';
                return '<p class="font-semibold dark:text-navy-100 text-sm">' . e($row->title) . '</p>' . $cust;
            })
            // Template kept in the payload (template_name) but the column is hidden from the table.
            ->addColumn('domain_view', function ($row) {
                if (!$row->domain) return '-';
                $url = \Illuminate\Support\Str::startsWith($row->domain, 'http') ? $row->domain : 'https://' . $row->domain;
                return '<a href="' . e($url) . '" target="_blank" class="text-primary hover:underline dark:text-accent-light text-xs font-mono">'
                    . e($row->domain) . ' <i class="fa-solid fa-arrow-up-right-from-square text-[10px] ml-0.5"></i></a>';
            })
            ->addColumn('selected_view', function ($row) {
                $selected = (int) $row->is_selected === 1;
                $csrf = csrf_token();
                $title = $selected ? 'Unselect (remove star)' : 'Select as highlighted product';
                $btn = '<button type="button" data-id="' . $row->id . '" data-selected="' . ($selected ? 1 : 0) . "\" class=\"toggle-selected btn h-8 w-8 rounded-full p-0 text-sm transition-colors "
                    . ($selected
                        ? 'bg-amber-500/10 text-amber-500 hover:bg-amber-500/20'
                        : 'bg-slate-150 text-slate-400 hover:bg-amber-500/10 hover:text-amber-500 dark:bg-navy-500 dark:text-navy-300 dark:hover:bg-amber-500/20 dark:hover:text-amber-300')
                    . '" title="' . $title . '">'
                    . '<i class="' . ($selected ? 'fa-solid' : 'fa-regular') . ' fa-star"></i>'
                    . '</button>';
                return $btn;
            })
            ->addColumn('status_view', function ($row) {
                $active = (int) $row->is_active === 1;
                return '<span class="badge rounded-full px-2.5 py-0.5 text-xs font-semibold ' . ($active ? 'bg-success/10 text-success' : 'bg-error/10 text-error') . '">'
                    . ($active ? 'Active' : 'Inactive') . '</span>';
            })
            ->addColumn('action', function ($row) {
                $canEdit = canAccess('customers', $this->getProductId(), 'edit');
                $canDelete = canAccess('customers', $this->getProductId(), 'delete');
                $btn = '<div class="flex justify-end space-x-1.5">';

                if ($canEdit) {
                    $btn .= '<a href="' . route('admin.customers-website.page', $row->id) . '" class="btn h-8 w-8 rounded-full bg-info/10 p-0 font-medium text-info hover:bg-info/20 focus:bg-info/20" title="Manage Pages"><i class="fa-solid fa-list text-xs"></i></a>';
                    $btn .= '<a href="' . route('admin.customers-website.edit', $row->id) . '" class="btn h-8 w-8 rounded-full bg-info/10 p-0 font-medium text-info hover:bg-info/20 focus:bg-info/20" title="Edit Website"><i class="fa-solid fa-pen text-xs"></i></a>';
                }
                if ($canDelete) {
                    $btn .= '<form action="' . route('admin.customers-website.destroy', $row->id) . '" method="POST" class="inline-block" onsubmit="return confirm(\'Are you sure you want to delete this customer website?\')">';
                    $btn .= csrf_field() . method_field('DELETE');
                    $btn .= '<button type="submit" class="btn h-8 w-8 rounded-full bg-error/10 p-0 font-medium text-error hover:bg-error/20 focus:bg-error/20" title="Delete Website"><i class="fa-solid fa-trash text-xs"></i></button>';
                    $btn .= '</form>';
                }
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['title_view', 'selected_view', 'domain_view', 'status_view', 'action'])
            ->make(true);
    }

    public function create()
    {
        if (canAccess('customers', $this->getProductId(), 'add') == false) {
            return redirect()->route('admin.customers-website')->with('warning', 'Tidak Memiliki Akses');
        }

        $customers = Customer::orderBy('name', 'asc')->get();
        $templates = Template::orderBy('name', 'asc')->get();

        return view('admin.customers-website.create', [
            'customers' => $customers,
            'templates' => $templates,
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Create'
        ]);
    }

    public function store(Request $request)
    {
        if (canAccess('customers', $this->getProductId(), 'add') == false) {
            return redirect()->back()->with('error', 'Tidak Memiliki Akses');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'template_id' => 'nullable|exists:template,id',
            'title' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'qr_payment' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_qr_payment' => 'nullable|boolean',
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'x' => 'nullable|string|max:255',
            'threads' => 'nullable|string|max:255',
            'shopee' => 'nullable|string|max:255',
            'tokopedia' => 'nullable|string|max:255',

            // Website Identity (opsional)
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
            'remove_logo' => 'nullable|boolean',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'accent_color' => 'nullable|string|max:20',
            'primary_font' => 'nullable|string|max:100',
            'secondary_font' => 'nullable|string|max:100',
            'font_color' => 'nullable|string|max:20',
        ]);

        $qrPath = $this->handleQrUpload($request, null);

        $website = CustomersWebsite::create([
            'customer_id' => $request->customer_id,
            'template_id' => $request->template_id,
            'title' => $request->title,
            'domain' => $request->domain,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'qr_payment' => $qrPath,
            'instagram' => $request->instagram,
            'tiktok' => $request->tiktok,
            'facebook' => $request->facebook,
            'x' => $request->x,
            'threads' => $request->threads,
            'shopee' => $request->shopee,
            'tokopedia' => $request->tokopedia,
        ]);

        $this->saveIdentity($request, $website);

        return redirect()->route('admin.customers-website')->with('success', 'Customer Website created successfully.');
    }

    public function edit($id)
    {
        if (canAccess('customers', $this->getProductId(), 'edit') == false) {
            return redirect()->route('admin.customers-website')->with('warning', 'Tidak Memiliki Akses');
        }

        $website = CustomersWebsite::with('identity')->findOrFail($id);
        $customers = Customer::orderBy('name', 'asc')->get();
        $templates = Template::orderBy('name', 'asc')->get();

        return view('admin.customers-website.edit', [
            'website' => $website,
            'customers' => $customers,
            'templates' => $templates,
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        if (canAccess('customers', $this->getProductId(), 'edit') == false) {
            return redirect()->back()->with('error', 'Tidak Memiliki Akses');
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'template_id' => 'nullable|exists:template,id',
            'title' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'qr_payment' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_qr_payment' => 'nullable|boolean',
            'instagram' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'x' => 'nullable|string|max:255',
            'threads' => 'nullable|string|max:255',
            'shopee' => 'nullable|string|max:255',
            'tokopedia' => 'nullable|string|max:255',
        ]);

        $website = CustomersWebsite::findOrFail($id);
        $qrPath = $this->handleQrUpload($request, $website->qr_payment);

        $website->update([

            'customer_id' => $request->customer_id,
            'template_id' => $request->template_id,
            'title' => $request->title,
            'domain' => $request->domain,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'qr_payment' => $qrPath,
            'instagram' => $request->instagram,
            'tiktok' => $request->tiktok,
            'facebook' => $request->facebook,
            'x' => $request->x,
            'threads' => $request->threads,
            'shopee' => $request->shopee,
            'tokopedia' => $request->tokopedia,
        ]);

        $this->saveIdentity($request, $website);

        return redirect()->route('admin.customers-website')->with('success', 'Customer Website updated successfully.');
    }

    public function destroy($id)
    {
        if (canAccess('customers', $this->getProductId(), 'delete') == false) {
            return redirect()->back()->with('error', 'Tidak Memiliki Akses');
        }

        $website = CustomersWebsite::findOrFail($id);
        $website->delete();

        return redirect()->route('admin.customers-website')->with('success', 'Customer Website deleted successfully.');
    }

    public function duplicate($id)
    {
        if (canAccess('customers', $this->getProductId(), 'add') == false) {
            return redirect()->back()->with('error', 'Tidak Memiliki Akses');
        }

        $website = CustomersWebsite::findOrFail($id);

        // Create new domain with -dupe suffix
        $newDomain = $website->domain ? $website->domain . '-dupe' : null;

        // Check if domain already exists, add number suffix if needed
        if ($newDomain) {
            $counter = 1;
            $originalDomain = $newDomain;
            while (CustomersWebsite::where('domain', $newDomain)->exists()) {
                $newDomain = $originalDomain . '-' . $counter;
                $counter++;
            }
        }

        // Duplicate the website
        $newWebsite = CustomersWebsite::create([
            'customer_id' => $website->customer_id,
            'template_id' => $website->template_id,
            'title' => $website->title . ' (Copy)',
            'domain' => $newDomain,
            'description' => $website->description,
            'is_active' => 0, // Set as inactive by default
            'instagram' => $website->instagram,
            'tiktok' => $website->tiktok,
            'facebook' => $website->facebook,
            'x' => $website->x,
            'threads' => $website->threads,
            'shopee' => $website->shopee,
            'tokopedia' => $website->tokopedia,
        ]);

        // Duplicate identity (logo, colors, fonts) — logo file is copied so each
        // website owns its own file
        if ($website->identity) {
            $identityData = $website->identity->only([
                'logo', 'primary_color', 'secondary_color', 'accent_color',
                'primary_font', 'secondary_font', 'font_color',
            ]);

            if (!empty($identityData['logo']) && file_exists(public_path($identityData['logo']))) {
                $oldLogo = public_path($identityData['logo']);
                $ext = pathinfo($oldLogo, PATHINFO_EXTENSION);
                $newName = time() . '_logo_' . uniqid() . '.' . $ext;
                $domainFolder = $newWebsite->domain ?: 'shared';
                $targetDir = public_path('images/website/' . $domainFolder);
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                copy($oldLogo, $targetDir . '/' . $newName);
                $identityData['logo'] = 'images/website/' . $domainFolder . '/' . $newName;
            }

            $newWebsite->identity()->create($identityData);
        }

        // Duplicate all layout items
        $layouts = CustomersWebsiteLayout::where('customers_website_id', $id)->get();
        foreach ($layouts as $layout) {
            // Copy content and update image paths if needed
            $newContent = $layout->content;

            CustomersWebsiteLayout::create([
                'customers_website_id' => $newWebsite->id,
                'templates_section_id' => $layout->templates_section_id,
                'template_content_id' => $layout->template_content_id,
                'page_type' => $layout->page_type,
                'content' => $newContent,
                'status' => $layout->status,
                'position' => $layout->position,
            ]);
        }

        return redirect()->route('admin.customers-website.edit', $newWebsite->id)
            ->with('success', 'Website duplicated successfully. Domain: ' . ($newDomain ?? 'N/A'));
    }

    // =================== WEBSITE IDENTITY ===================

    /**
     * Create/update the identity row of a customer website
     * (logo, colors, fonts). Logo upload replaces the old file on disk.
     */
    private function saveIdentity(Request $request, CustomersWebsite $website): void
    {
        $identity = $website->identity;
        $logoPath = $identity->logo ?? null;

        // Explicit removal
        if ($request->boolean('remove_logo')) {
            if ($logoPath && file_exists(public_path($logoPath))) {
                @unlink(public_path($logoPath));
            }
            $logoPath = null;
        }

        // New upload replaces the old file
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            if ($logoPath && file_exists(public_path($logoPath))) {
                @unlink(public_path($logoPath));
            }

            $file = $request->file('logo');
            $ext = strtolower($file->getClientOriginalExtension());
            $filename = time() . '_logo_' . uniqid() . '.' . $ext;

            $domainFolder = $website->domain ?: 'shared';
            $targetDir = public_path('images/website/' . $domainFolder);
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $file->move($targetDir, $filename);
            $logoPath = 'images/website/' . $domainFolder . '/' . $filename;
        }

        $payload = [
            'logo'            => $logoPath,
            'primary_color'   => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'accent_color'    => $request->accent_color,
            'primary_font'    => $request->primary_font,
            'secondary_font'  => $request->secondary_font,
            'font_color'      => $request->font_color,
        ];

        if ($identity) {
            $identity->update($payload);
        } else {
            $website->identity()->create($payload);
        }
    }

    // =================== SELECTED PRODUCT (STAR) ===================

    /**
     * Toggle the "Selected Product" star for a customer website (AJAX).
     */
    public function toggleSelected(Request $request, $id)
    {
        if (canAccess('customers', $this->getProductId(), 'edit') == false) {
            return response()->json(['success' => false, 'message' => 'Tidak Memiliki Akses'], 403);
        }

        $request->validate([
            'is_selected' => 'required|boolean',
        ]);

        $website = CustomersWebsite::findOrFail($id);
        $website->update([
            'is_selected' => $request->boolean('is_selected') ? 1 : 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => $website->is_selected
                ? 'Website ditandai sebagai selected product.'
                : 'Website dihapus dari selected product.',
            'is_selected' => (int) $website->is_selected,
        ]);
    }

    // =================== QR PAYMENT ===================

    /**
     * Handle the QR payment image upload.
     *
     * Returns the stored path (or null when removed / not provided).
     * Replaces the old image (and deletes it from disk) when a new one is uploaded
     * or when the remove checkbox is ticked.
     */
    private function handleQrUpload(Request $request, ?string $currentPath): ?string
    {
        $qrDir = public_path('images/qr_payment');
        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        // Explicit removal
        if ($request->boolean('remove_qr_payment')) {
            if ($currentPath && file_exists(public_path($currentPath))) {
                @unlink(public_path($currentPath));
            }
            return null;
        }

        // New upload replaces the old file
        if ($request->hasFile('qr_payment') && $request->file('qr_payment')->isValid()) {
            if ($currentPath && file_exists(public_path($currentPath))) {
                @unlink(public_path($currentPath));
            }

            $file = $request->file('qr_payment');
            $filename = time() . '_qr_' . uniqid() . '.' . strtolower($file->getClientOriginalExtension());
            $file->move($qrDir, $filename);

            return 'images/qr_payment/' . $filename;
        }

        // Keep the current image
        return $currentPath;
    }

    // =================== LAYOUT METHODS ===================

    public function page($id)
    {
        $website = CustomersWebsite::with(['customer', 'template'])->findOrFail($id);
        $layouts = CustomersWebsiteLayout::where('customers_website_id', $id)->get();

        return view('admin.customers-website.page', [
            'website' => $website,
            'layouts' => $layouts,
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Pages'
        ]);
    }

    public function layout($id, $page_type)
    {
        $website = CustomersWebsite::with('template')->findOrFail($id);

        // Get sections from the website's template
        $sections = TemplatesSection::with(['contents', 'template'])
            // ->where('template_id', $website->template_id)
            ->orderBy('template_id', 'asc')
            ->orderBy('position', 'asc')
            ->get();

        // Get existing layout items for this website filtered by page_type
        $layouts = CustomersWebsiteLayout::with(['section.contents'])
            ->where('customers_website_id', $id)
            ->where('page_type', $page_type)
            ->orderBy('position', 'asc')
            ->get();

        return view('admin.customers-website.layout', [
            'website' => $website,
            'sections' => $sections,
            'layouts' => $layouts,
            'page_type' => $page_type,
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Layout'
        ]);
    }

    public function getSectionContents($sectionId)
    {
        $contents = TemplatesSectionContent::where('templates_sections_id', $sectionId)->get();
        return response()->json([
            'success' => true,
            'contents' => $contents
        ]);
    }

    /**
     * Recursively scan data for base64 image data URIs, save them as files,
     * and replace the base64 strings with file paths.
     */
    private function processBase64Images(&$data, $websiteId)
    {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                $this->processBase64Images($value, $websiteId);
            }
            unset($value);
        } elseif (is_string($data) && preg_match('/^data:image\/(\w+);base64,/', $data, $matches)) {
            $website = CustomersWebsite::find($websiteId);
            $domainFolder = $website ? $website->domain : null;
            $ext = strtolower($matches[1]);
            if ($ext === 'jpeg') $ext = 'jpg';
            $filename = time() . '_' . uniqid() . '.' . $ext;

            $rawData = preg_replace('/^data:image\/\w+;base64,/', '', $data);
            $decoded = base64_decode($rawData);
            if ($decoded === false) return;

            if (!empty($domainFolder)) {
                $targetDir = public_path('images/website/' . $domainFolder);
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                file_put_contents($targetDir . '/' . $filename, $decoded);
                $data = $filename;
            } else {
                if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('layout_content')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('layout_content');
                }
                \Illuminate\Support\Facades\Storage::disk('public')->put('layout_content/' . $filename, $decoded);
                $data = 'layout_content/' . $filename;
            }
        }
    }

    /**
     * Recursively collect all image file paths from content data.
     * Handles nested repeater arrays and flat key-value pairs.
     */
    private function collectImageFiles($data, &$files)
    {
        if (is_array($data)) {
            foreach ($data as $value) {
                $this->collectImageFiles($value, $files);
            }
        } elseif (is_string($data) && !empty($data)) {
            // Skip base64 data URIs and non-file values
            if (str_starts_with($data, 'data:') || str_starts_with($data, 'http') || str_starts_with($data, '#')) {
                return;
            }
            $files[] = $data;
        }
    }

    /**
     * Delete image files from disk given an array of file paths.
     */
    private function deleteImageFiles($files, $websiteId)
    {
        $website = CustomersWebsite::find($websiteId);
        $domainFolder = $website ? $website->domain : null;

        foreach ($files as $filePath) {
            // Delete from domain folder
            if (!empty($domainFolder)) {
                $fullPath = public_path('images/website/' . $domainFolder . '/' . basename($filePath));
                if (file_exists($fullPath) && is_file($fullPath)) {
                    @unlink($fullPath);
                }
            }
            // Delete from storage layout_content
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
            }
            // Also try direct path (for files stored in layout_content)
            $directPath = public_path('storage/' . $filePath);
            if (file_exists($directPath) && is_file($directPath)) {
                @unlink($directPath);
            }
        }
    }

    public function layoutStore(Request $request, $id, $page_type)
    {
        $request->validate([
            'templates_section_id' => 'required|exists:templates_section,id',
            'template_content_id' => 'nullable|integer',
            'content' => 'nullable|string',
            'position' => 'nullable|integer',
        ]);

        $dynamicData = [];
        if ($request->has('dynamic_content') && is_array($request->dynamic_content)) {
            foreach ($request->dynamic_content as $k => $v) {
                if (is_string($v) && (str_starts_with(trim($v), '[') || str_starts_with(trim($v), '{'))) {
                    $decodedVal = json_decode($v, true);
                    $dynamicData[$k] = (json_last_error() === JSON_ERROR_NONE) ? $decodedVal : $v;
                } else {
                    $dynamicData[$k] = $v;
                }
            }
        }

        if ($request->hasFile('dynamic_files')) {
            $website = CustomersWebsite::find($id);
            $domainFolder = $website ? $website->domain : null;

            if (!empty($domainFolder)) {
                $targetDir = public_path('images/website/' . $domainFolder);
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                foreach ($request->file('dynamic_files') as $key => $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($targetDir, $filename);
                        $dynamicData[$key] = $filename;
                    }
                }
            } else {
                if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('layout_content')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('layout_content');
                }
                foreach ($request->file('dynamic_files') as $key => $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('layout_content', $filename, 'public');
                        $dynamicData[$key] = $path;
                    }
                }
            }
        }

        $finalContent = $request->content;
        $contentInputData = [];
        if (!empty($finalContent)) {
            $decodedInput = json_decode($finalContent, true);
            if (is_array($decodedInput)) {
                $contentInputData = $decodedInput;
            }
        }

        if (!empty($dynamicData) || !empty($contentInputData)) {
            $merged = array_merge($contentInputData, $dynamicData);
            $this->processBase64Images($merged, $id);
            $finalContent = json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        // Auto position: always append to end
        $maxPosition = CustomersWebsiteLayout::where('customers_website_id', $id)
            ->where('page_type', $page_type)
            ->max('position');

        $layout = CustomersWebsiteLayout::create([
            'customers_website_id' => $id,
            'templates_section_id' => $request->templates_section_id,
            'template_content_id' => $request->template_content_id,
            'page_type' => $page_type,
            'content' => $finalContent,
            'status' => $request->has('status') ? 1 : 0,
            'position' => ($maxPosition ?? 0) + 1,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Layout item added successfully.',
                'layout' => $layout,
            ]);
        }

        return redirect()->route('admin.customers-website.layout', [$id, $page_type])->with('success', 'Layout item added successfully.');
    }

    public function layoutUpdate(Request $request, $id, $page_type, $layoutId)
    {
        $request->validate([
            'templates_section_id' => 'required|exists:templates_section,id',
            'template_content_id' => 'nullable|integer',
            'content' => 'nullable|string',
            'position' => 'nullable|integer',
        ]);

        $layout = CustomersWebsiteLayout::where('customers_website_id', $id)->findOrFail($layoutId);

        $existingData = [];
        if (!empty($layout->content)) {
            $decoded = json_decode($layout->content, true);
            if (is_array($decoded)) {
                $existingData = $decoded;
            }
        }

        $dynamicData = [];
        if ($request->has('dynamic_content') && is_array($request->dynamic_content)) {
            foreach ($request->dynamic_content as $k => $v) {
                if (is_string($v) && (str_starts_with(trim($v), '['))) {
                    $decodedVal = json_decode($v, true);
                    $dynamicData[$k] = (json_last_error() === JSON_ERROR_NONE) ? $decodedVal : $v;
                } else if (is_string($v) && (str_starts_with(trim($v), '{'))) {
                    $decodedVal = json_decode($v, true);
                    $dynamicData[$k] = (json_last_error() === JSON_ERROR_NONE) ? $decodedVal : $v;
                } else {
                    $dynamicData[$k] = $v;
                }
            }
        }

        if ($request->hasFile('dynamic_files')) {
            $website = CustomersWebsite::find($id);
            $domainFolder = $website ? $website->domain : null;

            if (!empty($domainFolder)) {
                $targetDir = public_path('images/website/' . $domainFolder);
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                foreach ($request->file('dynamic_files') as $key => $file) {
                    if ($file->isValid()) {
                        // Delete old file if existing for this key
                        if (!empty($existingData[$key]) && is_string($existingData[$key])) {
                            $oldFilePath = $targetDir . '/' . basename($existingData[$key]);
                            if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                                @unlink($oldFilePath);
                            }
                        }

                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $file->move($targetDir, $filename);
                        $dynamicData[$key] = $filename;
                    }
                }
            } else {
                if (!\Illuminate\Support\Facades\Storage::disk('public')->exists('layout_content')) {
                    \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('layout_content');
                }
                foreach ($request->file('dynamic_files') as $key => $file) {
                    if ($file->isValid()) {
                        // Delete old file if existing
                        if (!empty($existingData[$key]) && is_string($existingData[$key])) {
                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($existingData[$key])) {
                                \Illuminate\Support\Facades\Storage::disk('public')->delete($existingData[$key]);
                            }
                        }

                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs('layout_content', $filename, 'public');
                        $dynamicData[$key] = $path;
                    }
                }
            }
        }

        $finalContent = $request->content;
        $contentInputData = [];
        if (!empty($finalContent)) {
            $decodedInput = json_decode($finalContent, true);
            if (is_array($decodedInput)) {
                $contentInputData = $decodedInput;
            }
        }

        // Combine inputs: dynamicData (form inputs) takes precedence over contentInputData (textarea), which takes precedence over existing DB data
        $merged = array_merge($existingData, $contentInputData, $dynamicData);
        if (!empty($merged)) {
            $this->processBase64Images($merged, $id);

            // Cleanup: delete old image files no longer present in the new content
            $oldFiles = [];
            $newFiles = [];
            $this->collectImageFiles($existingData, $oldFiles);
            $this->collectImageFiles($merged, $newFiles);
            $removedFiles = array_diff($oldFiles, $newFiles);
            if (!empty($removedFiles)) {
                $this->deleteImageFiles(array_values($removedFiles), $id);
            }

            $finalContent = json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }

        $layout->update([
            'templates_section_id' => $request->templates_section_id,
            'template_content_id' => $request->template_content_id,
            'page_type' => $page_type,
            'content' => $finalContent,
            'status' => $request->has('status') ? 1 : 0,
            'position' => $request->position ?? $layout->position,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Layout item updated successfully.',
                'layout' => $layout,
            ]);
        }

        return redirect()->route('admin.customers-website.layout', [$id, $page_type])->with('success', 'Layout item updated successfully.');
    }

    public function layoutDestroy(Request $request, $id, $page_type, $layoutId)
    {
        $layout = CustomersWebsiteLayout::where('customers_website_id', $id)->findOrFail($layoutId);
        
        if (!empty($layout->content)) {
            $contentData = json_decode($layout->content, true);
            if (is_array($contentData)) {
                $imageFiles = [];
                $this->collectImageFiles($contentData, $imageFiles);
                $this->deleteImageFiles($imageFiles, $id);
            }
        }

        $layout->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Layout item deleted successfully.',
                'layout_id' => $layoutId,
            ]);
        }

        return redirect()->route('admin.customers-website.layout', [$id, $page_type])->with('success', 'Layout item deleted successfully.');
    }

    public function layoutReorder(Request $request, $id, $page_type)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:customers_websites_layout,id',
        ]);

        foreach ($request->order as $position => $layoutId) {
            CustomersWebsiteLayout::where('id', $layoutId)
                ->where('customers_website_id', $id)
                ->update(['position' => $position]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Layout order updated successfully.',
        ]);
    }
}

