<?php

namespace App\Http\Controllers\admin;

/**
 * Controller for Admin Template CRUD with Upload & Popup
 */

use App\Http\Controllers\Controller;
use App\Models\Template;
use App\Models\TemplatesSection;
use App\Models\TemplatesSectionContent;
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
        $sections = TemplatesSection::with('contents')->where('template_id', $id)->orderBy('position', 'asc')->get();

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
            'position' => 'nullable|integer',
            'preview' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'new_content.key' => 'nullable|string|max:255',
            'new_content.value' => 'nullable|string',
            'new_content.type' => 'nullable|string|max:50',
            'new_content_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $previewPath = null;
        if ($request->hasFile('preview')) {
            $dir = public_path('uploads/templates');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $file = $request->file('preview');
            $filename = time() . '_section_preview_' . $file->getClientOriginalName();
            $file->move($dir, $filename);
            $previewPath = 'uploads/templates/' . $filename;
        }

        $section = TemplatesSection::create([
            'template_id' => $id,
            'name' => $request->name,
            'slug' => $request->slug ?? \Illuminate\Support\Str::slug($request->name),
            'status' => $request->has('status') ? 1 : 0,
            'position' => $request->position ?? 0,
            'preview' => $previewPath,
        ]);

        if ($request->has('new_content') && !empty($request->new_content['key'])) {
            $value = $request->new_content['value'] ?? null;
            
            if ($request->new_content['type'] === 'image' && $request->hasFile('new_content_image')) {
                $dir = public_path('uploads/templates');
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $file = $request->file('new_content_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($dir, $filename);
                $value = 'uploads/templates/' . $filename;
            }

            TemplatesSectionContent::create([
                'templates_sections_id' => $section->id,
                'key' => $request->new_content['key'],
                'value' => $value,
                'type' => $request->new_content['type'] ?? 'text',
            ]);
        }

        $section->load('contents');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Section has been created successfully.',
                'section' => $section,
            ]);
        }

        return redirect()->route('admin.template.section', $id)->with('success', 'Section has been created successfully.');
    }

    public function sectionUpdate(Request $request, $id, $sectionId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            'preview' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'contents' => 'nullable|array',
            'new_content.key' => 'nullable|string|max:255',
            'new_content.value' => 'nullable|string',
            'new_content.type' => 'nullable|string|max:50',
            'new_content_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'contents_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $section = TemplatesSection::findOrFail($sectionId);
        
        $previewPath = $section->preview;
        if ($request->hasFile('preview')) {
            $dir = public_path('uploads/templates');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            
            // Delete old preview if exists
            if ($section->preview && file_exists(public_path($section->preview))) {
                @unlink(public_path($section->preview));
            }

            $file = $request->file('preview');
            $filename = time() . '_section_preview_' . $file->getClientOriginalName();
            $file->move($dir, $filename);
            $previewPath = 'uploads/templates/' . $filename;
        }

        $section->update([
            'name' => $request->name,
            'slug' => $request->slug ?? \Illuminate\Support\Str::slug($request->name),
            'status' => $request->has('status') ? 1 : 0,
            'position' => $request->position ?? $section->position,
            'preview' => $previewPath,
        ]);

        // Update existing content items
        if ($request->has('contents') && is_array($request->contents)) {
            foreach ($request->contents as $contentId => $item) {
                $contentModel = TemplatesSectionContent::where('templates_sections_id', $section->id)
                    ->where('id', $contentId)
                    ->first();
                if ($contentModel) {
                    $value = $item['value'] ?? null;

                    // Check if an image is uploaded for this existing content
                    if ($item['type'] === 'image' && $request->hasFile("contents_images.{$contentId}")) {
                        $dir = public_path('uploads/templates');
                        if (!file_exists($dir)) {
                            mkdir($dir, 0755, true);
                        }
                        
                        // Delete old image if exists
                        if ($contentModel->value && file_exists(public_path($contentModel->value))) {
                            @unlink(public_path($contentModel->value));
                        }

                        $file = $request->file("contents_images.{$contentId}");
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $file->move($dir, $filename);
                        $value = 'uploads/templates/' . $filename;
                    } elseif ($item['type'] === 'image' && empty($value)) {
                        // Preserve old value if type is image and no new upload / empty value passed
                        $value = $contentModel->value;
                    }

                    $contentModel->update([
                        'key' => $item['key'] ?? $contentModel->key,
                        'type' => $item['type'] ?? $contentModel->type,
                        'value' => $value,
                    ]);
                }
            }
        }

        // Add new content item if provided
        $newContentCreated = null;
        if ($request->has('new_content') && !empty($request->new_content['key'])) {
            $value = $request->new_content['value'] ?? null;

            if ($request->new_content['type'] === 'image' && $request->hasFile('new_content_image')) {
                $dir = public_path('uploads/templates');
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                $file = $request->file('new_content_image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($dir, $filename);
                $value = 'uploads/templates/' . $filename;
            }

            $newContentCreated = TemplatesSectionContent::create([
                'templates_sections_id' => $section->id,
                'key' => $request->new_content['key'],
                'type' => $request->new_content['type'] ?? 'text',
                'value' => $value,
            ]);
        }

        $section->load('contents');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Section has been updated successfully.',
                'section' => $section,
                'new_content' => $newContentCreated,
            ]);
        }

        return redirect()->route('admin.template.section', $id)->with('success', 'Section has been updated successfully.');
    }

    public function sectionDestroy(Request $request, $id, $sectionId)
    {
        $section = TemplatesSection::findOrFail($sectionId);
        TemplatesSectionContent::where('templates_sections_id', $section->id)->delete();
        $section->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Section has been deleted successfully.',
                'section_id' => $sectionId,
            ]);
        }

        return redirect()->route('admin.template.section', $id)->with('success', 'Section has been deleted successfully.');
    }

    public function sectionContentDestroy(Request $request, $id, $contentId)
    {
        $content = TemplatesSectionContent::findOrFail($contentId);
        $content->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Section content item deleted successfully.',
                'content_id' => $contentId,
            ]);
        }

        return redirect()->route('admin.template.section', $id)->with('success', 'Section content item deleted successfully.');
    }
}
