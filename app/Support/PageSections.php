<?php

namespace App\Support;

/**
 * Registry + validator untuk section page-builder.
 *
 * @see PRD.md 5.3-5.4
 */
class PageSections
{
    public const VERSION = 1;

    /**
     * @var array<string, array<string, mixed>>
     */
    public const TYPES = [
        'hero' => ['label' => 'Hero'],
        'statistics' => ['label' => 'Statistics'],
        'principal' => ['label' => 'Sambutan Kepala Sekolah'],
        'programs' => ['label' => 'Programs'],
        'values' => ['label' => 'Nilai & Karakter'],
        'latest_news' => ['label' => 'Latest News'],
        'news' => ['label' => 'News'],
        'achievements' => ['label' => 'Achievements'],
        'facilities' => ['label' => 'Facilities'],
        'gallery' => ['label' => 'Gallery'],
        'rich_text' => ['label' => 'Rich Text'],
        'image' => ['label' => 'Image'],
        'image_gallery' => ['label' => 'Image Gallery'],
        'card_grid' => ['label' => 'Card Grid'],
        'teachers' => ['label' => 'Teachers'],
        'announcements' => ['label' => 'Announcements'],
        'cta' => ['label' => 'CTA'],
        'contact' => ['label' => 'Contact'],
        'faq' => ['label' => 'FAQ'],
        'custom_html' => ['label' => 'Custom HTML'],
    ];

    /** @return list<string> */
    public static function types(): array
    {
        return array_keys(self::TYPES);
    }

    /**
     * Normalisasi payload sections menjadi struktur konseptual PRD.
     *
     * @return array{version:int, sections:array<int, array{id:string, type:string, data:array<string,mixed>}>}
     */
    public static function normalize(mixed $sections): array
    {
        if (is_string($sections)) {
            $decoded = json_decode($sections, true);
            $sections = is_array($decoded) ? $decoded : [];
        }

        if (! is_array($sections)) {
            return ['version' => self::VERSION, 'sections' => []];
        }

        // Dukung dua bentuk: {version, sections:[...]} atau langsung [...].
        $items = $sections;
        if (array_key_exists('sections', $sections) && is_array($sections['sections'])) {
            $items = $sections['sections'];
        }

        $normalized = [];
        foreach ($items as $index => $section) {
            if (! is_array($section)) {
                continue;
            }

            $type = (string) ($section['type'] ?? '');
            if (! array_key_exists($type, self::TYPES)) {
                continue;
            }

            $id = (string) ($section['id'] ?? $type.'-'.($index + 1));
            $data = is_array($section['data'] ?? null) ? $section['data'] : [];

            // Keamanan: jangan pernah simpan JS bebas dari CMS.
            if ($type === 'custom_html') {
                $data['html'] = self::sanitizeHtml((string) ($data['html'] ?? ''));
            }

            $normalized[] = ['id' => $id, 'type' => $type, 'data' => $data];
        }

        return ['version' => self::VERSION, 'sections' => $normalized];
    }

    /**
     * State Builder Filament (flat list) dari payload tersimpan.
     *
     * Kolom DB memakai bentuk {version, sections:[{id,type,data}]},
     * sedangkan Builder Filament butuh list [{type,data}].
     *
     * @return array<int, array{type:string, data:array<string,mixed>}>
     */
    public static function builderStateFromStored(mixed $sections): array
    {
        $normalized = self::normalize($sections);

        return array_map(
            fn (array $section): array => [
                'type' => $section['type'],
                'data' => $section['data'],
            ],
            $normalized['sections'],
        );
    }

    /**
     * Kembalikan state Builder menjadi payload tersimpan.
     *
     * Id section + key yang tidak dimodelkan di form admin dipertahankan
     * (dicocokkan per type agar stabil saat item di-reorder/tambah/hapus).
     *
     * @param  array<int|string, mixed>  $items
     * @return array{version:int, sections:array<int, array{id:string, type:string, data:array<string,mixed>}>}
     */
    public static function storedFromBuilderState(array $items, mixed $originalStored): array
    {
        $original = self::normalize($originalStored)['sections'];

        $byType = [];
        foreach ($original as $section) {
            $byType[$section['type']][] = $section;
        }

        $merged = [];
        $position = 0;
        foreach (array_values($items) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $type = (string) ($item['type'] ?? '');
            if (! array_key_exists($type, self::TYPES)) {
                continue;
            }

            $position++;
            $previous = null;
            if (! empty($byType[$type])) {
                $previous = array_shift($byType[$type]);
            }

            $data = is_array($item['data'] ?? null) ? $item['data'] : [];
            if ($previous !== null) {
                $data = array_replace_recursive($previous['data'], $data);
            }

            $merged[] = [
                'id' => $previous['id'] ?? $type.'-'.$position,
                'type' => $type,
                'data' => $data,
            ];
        }

        // Lewatkan normalize() agar custom_html tersanitasi & bentuk konsisten.
        return self::normalize($merged);
    }

    /**
     * Sajikan data array (items/images/cards) sebagai teks yang bisa diedit
     * di Textarea: daftar skalar jadi satu-per-baris, sisanya JSON pretty.
     */
    public static function editableText(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            if ($value === []) {
                return null;
            }

            $scalarsOnly = true;
            foreach ($value as $item) {
                if (! is_scalar($item) && $item !== null) {
                    $scalarsOnly = false;
                    break;
                }
            }

            if ($scalarsOnly) {
                return implode("\n", array_map(fn ($item): string => (string) $item, $value));
            }

            $json = json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

            return is_string($json) ? $json : null;
        }

        return null;
    }

    /**
     * Aturan validasi untuk field sections.
     *
     * @return array<string, mixed>
     */
    public static function validationRules(): array
    {
        return [
            'sections' => ['nullable', 'array'],
            'sections.version' => ['nullable', 'integer'],
            'sections.sections' => ['nullable', 'array'],
            'sections.sections.*.id' => ['required_with:sections.sections', 'string', 'max:100'],
            'sections.sections.*.type' => ['required_with:sections.sections', 'string', 'in:'.implode(',', self::types())],
            'sections.sections.*.data' => ['nullable', 'array'],
        ];
    }

    public static function sanitizeHtml(string $html): string
    {
        // Hapus <script>/<iframe>/<object> + event handler. Renderer Vue memakai v-html apa adanya.
        $html = (string) preg_replace('#<(script|iframe|object|embed)[^>]*?>.*?</\\1>#is', '', $html);
        $html = (string) preg_replace('#\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $html);
        $html = (string) preg_replace('#(href|src)\s*=\s*["\']\s*javascript:[^"\']*["\']#i', '$1="#"', $html);

        return trim($html);
    }
}
