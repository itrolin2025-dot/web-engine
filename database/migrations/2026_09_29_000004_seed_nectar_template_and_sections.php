<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Seed template "Nectar" (folder template/candy) beserta section dan
     * konten default-nya — pola yang sama dengan data Costica:
     * template.path = "template.candy", section slug = nama file blade,
     * konten default per section di templates_sections_content.
     */
    public function up(): void
    {
        // Idempotent: jangan dobel bila sudah ada (mis. migration dijalankan ulang)
        $existing = DB::table('template')->where('path', 'template.candy')->first();
        if ($existing) {
            return;
        }

        $templateId = DB::table('template')->insertGetId([
            'name'       => 'Nectar',
            'path'       => 'template.candy',
            'preview'    => null,
            'status'     => true,
            'position'   => (DB::table('template')->max('position') ?? 0) + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sections = [
            [
                'slug' => 'navbar', 'name' => 'Navbar', 'position' => 1,
                'contents' => [
                    ['key' => 'title', 'type' => 'text', 'value' => 'nectar'],
                    ['key' => 'title_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'background_color', 'type' => 'color', 'value' => '#f8b8cf'],
                ],
            ],
            [
                'slug' => 'hero', 'name' => 'Hero Banner', 'position' => 2,
                'contents' => [
                    ['key' => 'tag', 'type' => 'text', 'value' => '(Based in Pittsburgh, PA)'],
                    ['key' => 'tag_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'title', 'type' => 'text', 'value' => "The Swedish Candy Cart\nof Your Dreams"],
                    ['key' => 'title_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'subtitle', 'type' => 'long_text', 'value' => 'Your go-to cart to call for events and catering, monthly candy club deliveries, online candy fixes, and more.'],
                    ['key' => 'subtitle_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'image', 'type' => 'image', 'value' => 'your image'],
                    ['key' => 'button_text', 'type' => 'text', 'value' => 'Join the Club'],
                    ['key' => 'button_color', 'type' => 'color', 'value' => '#c2b2f0'],
                    ['key' => 'button_text_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'background_color', 'type' => 'color', 'value' => '#fcf8f5'],
                ],
            ],
            [
                'slug' => 'schedule', 'name' => 'Schedule', 'position' => 3,
                'contents' => [
                    ['key' => 'tag', 'type' => 'text', 'value' => 'Where to Catch the Cart'],
                    ['key' => 'tag_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'title', 'type' => 'text', 'value' => 'THIS WEEK'],
                    ['key' => 'title_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'subtitle', 'type' => 'text', 'value' => 'PSST! GET THE SCHEDULE IN YOUR INBOX EVERY WEEK!'],
                    ['key' => 'subtitle_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'button_text', 'type' => 'text', 'value' => 'Find Us Today'],
                    ['key' => 'button_color', 'type' => 'color', 'value' => '#721c2e'],
                    ['key' => 'button_text_color', 'type' => 'color', 'value' => '#ffffff'],
                    ['key' => 'repeater', 'type' => 'repeater', 'value' => json_encode([
                        ['label' => 'MAR 03 (TUE)', 'title' => 'None', 'subtitle' => '', 'image' => '', 'sort' => '1'],
                        ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🍩', 'sort' => '2'],
                        ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🧸', 'sort' => '3'],
                        ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🍫', 'sort' => '4'],
                        ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🍒', 'sort' => '5'],
                        ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🍬', 'sort' => '6'],
                        ['label' => 'MAR 03 (TUE)', 'title' => 'Rocko Candy Vendor', 'subtitle' => '3:00-4:00pm', 'image' => '🥤', 'sort' => '7'],
                    ])],
                ],
            ],
            [
                'slug' => 'event', 'name' => 'Event', 'position' => 4,
                'contents' => [
                    ['key' => 'tag', 'type' => 'text', 'value' => 'WEDDINGS · BIRTHDAYS · POP-UPS & MORE'],
                    ['key' => 'tag_color', 'type' => 'color', 'value' => '#611328'],
                    ['key' => 'title', 'type' => 'text', 'value' => "Your event called.\nIt wants candy!"],
                    ['key' => 'title_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'subtitle', 'type' => 'long_text', 'value' => 'Candy concierge-guided tastings, a mix-your-own candy bag for every guest, and a candy cart that doubles as a conversation piece.'],
                    ['key' => 'subtitle_color', 'type' => 'color', 'value' => '#4a1525'],
                    ['key' => 'image', 'type' => 'image', 'value' => 'your image'],
                    ['key' => 'button_text', 'type' => 'text', 'value' => 'PLAN YOUR EVENT'],
                    ['key' => 'button_color', 'type' => 'color', 'value' => '#c23b59'],
                    ['key' => 'button_text_color', 'type' => 'color', 'value' => '#ffffff'],
                    ['key' => 'background_color', 'type' => 'color', 'value' => '#FAF6F0'],
                    ['key' => 'repeater', 'type' => 'repeater', 'value' => json_encode([
                        ['label' => '$399', 'title' => 'CART FEE (3HRS)', 'sort' => '1'],
                        ['label' => '$8', 'title' => 'PER GUEST', 'sort' => '2'],
                        ['label' => '$99', 'title' => 'PER ADDED HR', 'sort' => '3'],
                    ])],
                ],
            ],
            [
                'slug' => 'club', 'name' => 'Candy Club', 'position' => 5,
                'contents' => [
                    ['key' => 'tag', 'type' => 'text', 'value' => 'MONTHLY CANDY SUBSCRIPTION'],
                    ['key' => 'tag_color', 'type' => 'color', 'value' => '#721C24'],
                    ['key' => 'title', 'type' => 'text', 'value' => 'Join the Nectar Candy Club'],
                    ['key' => 'title_color', 'type' => 'color', 'value' => '#3D2314'],
                    ['key' => 'subtitle', 'type' => 'text', 'value' => '$25/month'],
                    ['key' => 'subtitle_color', 'type' => 'color', 'value' => '#3D2314'],
                    ['key' => 'description', 'type' => 'long_text', 'value' => 'Candy concierge-guided tastings, a mix-your-own candy bag for every guest, and a candy cart that doubles as a conversation piece.'],
                    ['key' => 'description_color', 'type' => 'color', 'value' => '#5B4636'],
                    ['key' => 'button_text', 'type' => 'text', 'value' => 'JOIN THE CLUB NOW'],
                    ['key' => 'button_color', 'type' => 'color', 'value' => '#C84B31'],
                    ['key' => 'button_text_color', 'type' => 'color', 'value' => '#ffffff'],
                    ['key' => 'background_color', 'type' => 'color', 'value' => '#B8B4FF'],
                ],
            ],
        ];

        foreach ($sections as $section) {
            $sectionId = DB::table('templates_section')->insertGetId([
                'template_id' => $templateId,
                'name'        => $section['name'],
                'slug'        => $section['slug'],
                'preview'     => null,
                'status'      => true,
                'position'    => $section['position'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            foreach ($section['contents'] as $content) {
                DB::table('templates_sections_content')->insert([
                    'templates_sections_id' => $sectionId,
                    'key'                   => $content['key'],
                    'value'                 => $content['value'],
                    'type'                  => $content['type'],
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        $template = DB::table('template')->where('path', 'template.candy')->first();
        if (!$template) {
            return;
        }

        $sectionIds = DB::table('templates_section')
            ->where('template_id', $template->id)
            ->pluck('id');

        DB::table('templates_sections_content')
            ->whereIn('templates_sections_id', $sectionIds)
            ->delete();

        DB::table('templates_section')->where('template_id', $template->id)->delete();
        DB::table('template')->where('id', $template->id)->delete();
    }
};
