<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomersWebsite;
use App\Models\CustomersWebsiteLayout;
use App\Models\Template;
use App\Models\TemplatesSection;
use App\Models\TemplatesSectionContent;
use Illuminate\Http\Request;

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
            'template_id' => 'required|exists:template,id',
            'title' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        CustomersWebsite::create([
            'customer_id' => $request->customer_id,
            'template_id' => $request->template_id,
            'title' => $request->title,
            'domain' => $request->domain,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.customers-website')->with('success', 'Customer Website created successfully.');
    }

    public function edit($id)
    {
        if (canAccess('customers', $this->getProductId(), 'edit') == false) {
            return redirect()->route('admin.customers-website')->with('warning', 'Tidak Memiliki Akses');
        }

        $website = CustomersWebsite::findOrFail($id);
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
            'template_id' => 'required|exists:template,id',
            'title' => 'required|string|max:255',
            'domain' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $website = CustomersWebsite::findOrFail($id);
        $website->update([
            'customer_id' => $request->customer_id,
            'template_id' => $request->template_id,
            'title' => $request->title,
            'domain' => $request->domain,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

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

        $layout = CustomersWebsiteLayout::create([
            'customers_website_id' => $id,
            'templates_section_id' => $request->templates_section_id,
            'template_content_id' => $request->template_content_id,
            'page_type' => $page_type,
            'content' => $finalContent,
            'status' => $request->has('status') ? 1 : 0,
            'position' => $request->position ?? 0,
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
}

