<?php

namespace App\Http\Controllers\admin;

/**
 * Controller for Admin Template CRUD with Upload & Popup
 */

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplatesSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    protected $modul = "template";
    protected $path = "template";
    protected $modul_name = "Template";
    protected $product_id;

    protected function getProductId()
    {
        return auth()->check() ? auth()->user()->product_id : null;
    }

    public function index()
    {
        if (canAccess($this->modul, $this->getProductId(), 'view') == false) {
            return redirect()->route('admin.dashboard');
        }

        $templates = Template::orderBy('id', 'desc')->get();

        return view('admin.template.index', [
            'templates' => $templates,
            'canAdd' => canAccess($this->modul, $this->getProductId(), 'add'),
            'canEdit' => canAccess($this->modul, $this->getProductId(), 'edit'),
            'canDelete' => canAccess($this->modul, $this->getProductId(), 'delete'),
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'List'
        ]);

    }

    public function create()
    {
        if (canAccess($this->modul, $this->getProductId(), 'add') == false) {
            return redirect()->route('admin.template')->with('warning', 'Tidak Memiliki Akses');
        }

        return view('admin.template.create', [
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Create'
        ]);
    }

    public function store(Request $request)
    {
        if (canAccess($this->modul, $this->getProductId(), 'add') == false) {
            return redirect()->back()->with('error', 'Tidak Memiliki Akses');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'path' => 'nullable|string',
            'preview' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'path' => $request->path,
            'status' => $request->has('status') ? 1 : 0,
        ];

        if ($request->hasFile('preview')) {
            $file = $request->file('preview');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/templates'), $filename);
            $data['preview'] = 'uploads/templates/' . $filename;
        }

        Template::create($data);

        return redirect()->route('admin.template')->with('success', 'Template has been created successfully.');
    }

    public function edit($id)
    {
        if (canAccess($this->modul, $this->getProductId(), 'edit') == false) {
            return redirect()->route('admin.template')->with('warning', 'Tidak Memiliki Akses');
        }

        $template = Template::findOrFail($id);

        return view('admin.template.edit', [
            'template' => $template,
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Edit'
        ]);
    }

    public function update(Request $request, $id)
    {
        if (canAccess($this->modul, $this->getProductId(), 'edit') == false) {
            return redirect()->back()->with('error', 'Tidak Memiliki Akses');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'path' => 'nullable|string',
            'preview' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $template = Template::findOrFail($id);

        $data = [
            'name' => $request->name,
            'path' => $request->path,
            'status' => $request->has('status') ? 1 : 0,
        ];

        if ($request->hasFile('preview')) {
            // Delete old file if exists and file path exists
            if ($template->preview && file_exists(public_path($template->preview))) {
                @unlink(public_path($template->preview));
            }

            $file = $request->file('preview');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/templates'), $filename);
            $data['preview'] = 'uploads/templates/' . $filename;
        }

        $template->update($data);

        return redirect()->route('admin.template')->with('success', 'Template has been updated successfully.');
    }

    public function destroy($id)
    {
        if (canAccess($this->modul, $this->getProductId(), 'delete') == false) {
            return response()->json(['success' => false, 'message' => 'Tidak Memiliki Akses']);
        }

        $template = Template::findOrFail($id);

        if ($template->preview && file_exists(public_path($template->preview))) {
            @unlink(public_path($template->preview));
        }

        $template->delete();

        return redirect()->route('admin.template')->with('success', 'Template has been deleted successfully.');
    }

    public function section($id)
    {
        if (canAccess($this->modul, $this->getProductId(), 'edit') == false) {
            return redirect()->route('admin.template')->with('warning', 'Tidak Memiliki Akses');
        }

        $template = Template::findOrFail($id);
        $sections = TemplatesSection::where('template_id', $id)->orderBy('position', 'asc')->get();

        return view('admin.template.section', [
            'template' => $template,
            'sections' => $sections,
            'modul' => $this->modul,
            'modul_path' => $this->path,
            'modul_name' => $this->modul_name,
            'modul_type' => 'Section'
        ]);
    }

    public function sectionStore(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'type' => 'nullable|string',
            'position' => 'nullable|integer',
        ]);

        TemplatesSection::create([
            'template_id' => $id,
            'name' => $request->name,
            'slug' => $request->slug ?? \Illuminate\Support\Str::slug($request->name),
            'status' => $request->has('status') ? 1 : 0,
            'position' => $request->position ?? 0,
        ]);

        return redirect()->route('admin.template.section', $id)->with('success', 'Section has been created successfully.');
    }

    public function sectionUpdate(Request $request, $id, $sectionId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'type' => 'nullable|string',
            'position' => 'nullable|integer',
        ]);

        $section = TemplatesSection::findOrFail($sectionId);
        $section->update([
            'name' => $request->name,
            'slug' => $request->slug ?? \Illuminate\Support\Str::slug($request->name),
            'status' => $request->has('status') ? 1 : 0,
            'position' => $request->position ?? $section->position,
        ]);

        return redirect()->route('admin.template.section', $id)->with('success', 'Section has been updated successfully.');
    }

    public function sectionDestroy($id, $sectionId)
    {
        $section = TemplatesSection::findOrFail($sectionId);
        $section->delete();

        return redirect()->route('admin.template.section', $id)->with('success', 'Section has been deleted successfully.');
    }
}
