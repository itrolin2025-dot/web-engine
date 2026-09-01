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

        $contentPresets = $this->getContentPresets();
        $predefinedKeys = array_column($contentPresets, 'key');


        return view('admin.template.section', [
            'template' => $template,
            'sections' => $sections,
            'contentPresets' => $contentPresets,
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
            'section_contents' => 'nullable|array',
            'section_contents.*.enabled' => 'nullable',
            'section_contents.*.key' => 'nullable|string|max:255',
            'section_contents.*.type' => 'nullable|string|max:50',
            'section_contents.*.value' => 'nullable|string',
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

        // Save checked content presets
        if ($request->has('section_contents') && is_array($request->section_contents)) {
            foreach ($request->section_contents as $key => $item) {
                if (isset($item['enabled']) && $item['enabled'] == 1 && !empty($item['key'])) {
                    TemplatesSectionContent::create([
                        'templates_sections_id' => $section->id,
                        'key' => $item['key'],
                        'type' => $item['type'] ?? 'text',
                        'value' => $item['value'] ?? null,
                    ]);
                }
            }
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
            'section_contents' => 'nullable|array',
            'section_contents.*.enabled' => 'nullable',
            'section_contents.*.key' => 'nullable|string|max:255',
            'section_contents.*.type' => 'nullable|string|max:50',
            'section_contents.*.value' => 'nullable|string',
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

        // Handle section_contents checklist (insert/restore if checked, soft-delete if unchecked)
        if ($request->has('section_contents') && is_array($request->section_contents)) {
            
            $contentPresets = $this->getContentPresets();
            $predefinedKeys = array_column($contentPresets, 'key');

            foreach ($predefinedKeys as $key) {
                $item = $request->section_contents[$key] ?? null;
                $isEnabled = $item && isset($item['enabled']) && $item['enabled'] == 1;

                // Find existing record (including soft-deleted)
                $existing = TemplatesSectionContent::withTrashed()
                    ->where('templates_sections_id', $section->id)
                    ->where('key', $key)
                    ->first();

                if ($isEnabled) {
                    if ($existing) {
                        // Restore if soft-deleted, then update value
                        if ($existing->trashed()) {
                            $existing->restore();
                        }
                        $existing->update([
                            'type' => $item['type'] ?? $existing->type,
                            'value' => $item['value'] ?? $existing->value,
                        ]);
                    } else {
                        // Create new record
                        TemplatesSectionContent::create([
                            'templates_sections_id' => $section->id,
                            'key' => $key,
                            'type' => $item['type'] ?? 'text',
                            'value' => $item['value'] ?? null,
                        ]);
                    }
                } else {
                    // Not checked → soft delete if exists and not already deleted
                    if ($existing && !$existing->trashed()) {
                        $existing->delete();
                    }
                }
            }
        }

        $section->load('contents');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Section has been updated successfully.',
                'section' => $section,
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

    private function getContentPresets()
{
    return [
        ['key' => 'tag', 'type' => 'text', 'default_value' => 'your tag'],
        ['key' => 'tag_color', 'type' => 'color', 'default_value' => '#000000'],
        ['key' => 'title', 'type' => 'text', 'default_value' => 'your title'],
        ['key' => 'title_color', 'type' => 'color', 'default_value' => '#000000'],
        ['key' => 'subtitle', 'type' => 'text', 'default_value' => 'your subtitle'],
        ['key' => 'subtitle_color', 'type' => 'color', 'default_value' => '#000000'],
        ['key' => 'description', 'type' => 'long_text', 'default_value' => 'your description'],
        ['key' => 'description_color', 'type' => 'color', 'default_value' => '#000000'],
        ['key' => 'image', 'type' => 'image', 'default_value' => 'your image'],
        ['key' => 'background', 'type' => 'image', 'default_value' => 'your image'],
        ['key' => 'background_color', 'type' => 'color', 'default_value' => '#ffffff'],
        ['key' => 'button_text', 'type' => 'text', 'default_value' => 'check'],
        ['key' => 'button_url', 'type' => 'text', 'default_value' => '#'],
        ['key' => 'button_color', 'type' => 'color', 'default_value' => '#ffffff'],
        ['key' => 'button_border_color', 'type' => 'color', 'default_value' => '#ffffff'],
        ['key' => 'button_text_color', 'type' => 'color', 'default_value' => '#000000'],
        ['key' => 'repeater', 'type' => 'repeater', 'default_value' => '[{ "label": "Tag 1", "color":"#575757", "sort":"1" }, { "label": "Tag 2", "color":"#575757", "sort":"12" }]'],
        ['key' => 'data_product', 'type' => 'data', 'default_value' => 'false'],
        ['key' => 'data_article', 'type' => 'data', 'default_value' => 'false'],
    ];
}
}
