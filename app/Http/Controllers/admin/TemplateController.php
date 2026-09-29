<?php

namespace App\Http\Controllers\admin;

/**
 * Controller for Admin Template CRUD with Upload & Popup
 */

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Template;
use App\Models\TemplatesSection;
use App\Models\TemplatesSectionContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function index(Request $request)
    {
        if (canAccess($this->modul, $this->getProductId(), 'view') == false) {
            return redirect()->route('admin.dashboard');
        }

        $search = $request->input('search');
        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50]) ? (int) $request->input('per_page') : 10;

        $templates = Template::when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('path', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.template.index', [
            'templates' => $templates,
            'search' => $search,
            'perPage' => $perPage,
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
            'tags' => Tag::where('type', 'template')->orderBy('nama')->get(),
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
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
            'new_tag_names' => 'nullable|array',
            'new_tag_names.*' => 'nullable|string|max:255',
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

        DB::beginTransaction();
        try {
            $template = Template::create($data);
            $this->syncTemplateTags($template, $request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to create template: ' . $e->getMessage());
        }

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
            'tags' => Tag::where('type', 'template')->orderBy('nama')->get(),
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
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
            'new_tag_names' => 'nullable|array',
            'new_tag_names.*' => 'nullable|string|max:255',
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

        DB::beginTransaction();
        try {
            $template->update($data);
            $this->syncTemplateTags($template, $request);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update template: ' . $e->getMessage());
        }

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
        $sections = TemplatesSection::with(['contents', 'tags'])->where('template_id', $id)->orderBy('position', 'asc')->get();
        $sectionTags = Tag::where('type', 'section')->orderBy('nama')->get();

        $contentPresets = $this->getContentPresets();
        $predefinedKeys = array_column($contentPresets, 'key');


        return view('admin.template.section', [
            'template' => $template,
            'sections' => $sections,
            'sectionTags' => $sectionTags,
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
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
            'new_tag_names' => 'nullable|array',
            'new_tag_names.*' => 'nullable|string|max:255',
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

        // Auto position: always append to end
        $maxPosition = TemplatesSection::where('template_id', $id)->max('position');

        $section = TemplatesSection::create([
            'template_id' => $id,
            'name' => $request->name,
            'slug' => $request->slug ?? \Illuminate\Support\Str::slug($request->name),
            'status' => $request->has('status') ? 1 : 0,
            'position' => ($maxPosition ?? 0) + 1,
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

        $this->syncSectionTags($section, $request);

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
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
            'new_tag_names' => 'nullable|array',
            'new_tag_names.*' => 'nullable|string|max:255',
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

        $this->syncSectionTags($section, $request);

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

    public function sectionReorder(Request $request, $id)
    {
        $order = $request->input('order', []);

        foreach ($order as $position => $sectionId) {
            TemplatesSection::where('id', $sectionId)
                ->where('template_id', $id)
                ->update(['position' => $position]);
        }

        return response()->json(['success' => true, 'message' => 'Section order updated.']);
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

    /**
     * Sync template tags: existing checked tags + inline "add new tag" inputs
     * (new tags are always created with type 'template' and auto-generated code).
     */
    private function syncTemplateTags(Template $template, Request $request): void
    {
        $tagIds = collect($request->input('tags', []))->map(fn ($id) => (int) $id)->filter()->values();

        $newNames = collect($request->input('new_tag_names', []))
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique(fn ($name) => mb_strtolower($name));

        foreach ($newNames as $name) {
            $tag = Tag::whereRaw('LOWER(nama) = ?', [mb_strtolower($name)])->first();

            if (!$tag) {
                $tag = Tag::create([
                    'code' => Tag::generateCode(),
                    'nama' => $name,
                    'type' => 'template',
                ]);
            }

            $tagIds->push($tag->id);
        }

        $template->tags()->sync($tagIds->unique()->values());
    }

    /**
     * Sync section tags: existing checked tags + inline "add new tag" inputs
     * (new tags are always created with type 'section' and auto-generated code).
     */
    private function syncSectionTags(TemplatesSection $section, Request $request): void
    {
        $tagIds = collect($request->input('tags', []))->map(fn ($id) => (int) $id)->filter()->values();

        $newNames = collect($request->input('new_tag_names', []))
            ->map(fn ($name) => trim((string) $name))
            ->filter()
            ->unique(fn ($name) => mb_strtolower($name));

        foreach ($newNames as $name) {
            $tag = Tag::whereRaw('LOWER(nama) = ?', [mb_strtolower($name)])->first();

            if (!$tag) {
                $tag = Tag::create([
                    'code' => Tag::generateCode(),
                    'nama' => $name,
                    'type' => 'section',
                ]);
            }

            $tagIds->push($tag->id);
        }

        $section->tags()->sync($tagIds->unique()->values());
    }

    public static function getContentPresets()
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
