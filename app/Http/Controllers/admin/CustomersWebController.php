<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomersWebsite;
use App\Models\CustomersWebsiteLayout;
use App\Models\Template;
use App\Models\TemplatesSection;
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
        $layouts = CustomersWebsiteLayout::where('customers_website_id', $id)
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

    public function layoutStore(Request $request, $id, $page_type)
    {
        $request->validate([
            'templates_section_id' => 'required|exists:templates_section,id',
            'template_content_id' => 'nullable|integer',
            'content' => 'nullable|string',
            'position' => 'nullable|integer',
        ]);

        $layout = CustomersWebsiteLayout::create([
            'customers_website_id' => $id,
            'templates_section_id' => $request->templates_section_id,
            'template_content_id' => $request->template_content_id,
            'page_type' => $page_type,
            'content' => $request->content,
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
        $layout->update([
            'templates_section_id' => $request->templates_section_id,
            'template_content_id' => $request->template_content_id,
            'page_type' => $page_type,
            'content' => $request->content,
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

