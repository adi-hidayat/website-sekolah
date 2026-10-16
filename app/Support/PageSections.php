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
     * Ikon yang tersedia di komponen Vue AppIcon. Dipakai Select form agar
     * admin tidak mengetik nama ikon yang tidak dikenal.
     *
     * @var list<string>
     */
    public const ICONS = [
        'heart', 'academic-cap', 'shield-check', 'users', 'sparkles', 'book',
        'beaker', 'building', 'trophy', 'star', 'globe', 'quote', 'image',
        'user', 'check-circle', 'info', 'alert', 'clock', 'calendar',
        'map-pin', 'phone', 'mail', 'link', 'search', 'play', 'share',
    ];

    /**
     * Field data yang berupa daftar record (bukan hanya string) per tipe
     * section. Nilainya adalah key yang dipertahankan saat normalisasi.
     *
     * @var array<string, array<string, list<string>>>
     */
    private const RECORD_LISTS = [
        'card_grid' => ['cards' => ['icon', 'title', 'description', 'url', 'link_label']],
        'statistics' => ['items' => ['label', 'value']],
        'values' => ['items' => ['icon', 'title', 'description']],
        'faq' => ['items' => ['q', 'a']],
    ];

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
     * Pilihan ikon untuk Select form (value = label = nama ikon AppIcon).
     *
     * @return array<string, string>
     */
    public static function iconOptions(): array
    {
        return array_combine(self::ICONS, self::ICONS);
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

            // Galeri memakai FileUpload multiple (array). Normalisasi warisan
            // textarea satu-URL-per-baris / JSON menjadi array agar renderer
            // dan form admin konsisten.
            if (in_array($type, ['image_gallery', 'gallery'], true) && array_key_exists('images', $data)) {
                $data['images'] = self::normalizeImageList($data['images']);
            }

            // Field daftar record memakai Repeater di form admin. Normalisasi
            // warisan JSON/teks agar state Builder selalu array of record.
            foreach (self::RECORD_LISTS[$type] ?? [] as $field => $keys) {
                if (array_key_exists($field, $data)) {
                    $data[$field] = self::normalizeRecordList($data[$field], $keys);
                }
            }

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
     * Daftar gambar dinormalisasi ke array agar FileUpload multiple
     * bisa menampilkan data warisan (string satu-per-baris).
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
                $data = self::mergeSectionData($previous['data'], $data);
            }

            $merged[] = [
                'id' => $previous['id'] ?? $type.'-'.$position,
                'type' => $type,
                'data' => $data,
            ];
        }

        // Lewatkan normalize() agar custom_html tersanitasi, daftar record
        // di-reindex, dan bentuk payload konsisten.
        return self::normalize($merged);
    }

    /**
     * Normalisasi daftar gambar galeri ke array path/URL.
     *
     * Menerima warisan textarea (satu URL per baris / koma), JSON string,
     * array string, maupun array objek {src,url,image}. Selalu kembalikan
     * list berurutan agar cocok dengan FileUpload multiple dan renderer
     * frontend (toStringList).
     *
     * @return list<string>
     */
    public static function normalizeImageList(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            if ($trimmed === '') {
                return [];
            }

            if (str_starts_with($trimmed, '[')) {
                $decoded = json_decode($trimmed, true);

                if (is_array($decoded)) {
                    return self::normalizeImageList($decoded);
                }
            }

            $parts = preg_split('/\r?\n|,/', $trimmed);

            if ($parts === false) {
                return [];
            }

            return array_values(array_filter(
                array_map(fn (string $part): string => trim($part), $parts),
                fn (string $part): bool => $part !== ''
            ));
        }

        if (is_array($value)) {
            $output = [];

            foreach ($value as $item) {
                if (is_string($item)) {
                    $trimmed = trim($item);

                    if ($trimmed !== '') {
                        $output[] = $trimmed;
                    }
                } elseif (is_scalar($item)) {
                    $trimmed = trim((string) $item);

                    if ($trimmed !== '') {
                        $output[] = $trimmed;
                    }
                } elseif (is_array($item)) {
                    $src = $item['src'] ?? $item['url'] ?? $item['image'] ?? null;

                    if (is_string($src) && trim($src) !== '') {
                        $output[] = trim($src);
                    }
                }
            }

            return $output;
        }

        return [];
    }

    /**
     * Normalisasi daftar record section (cards/items/faq) menjadi array of
     * record agar cocok dengan Repeater di form admin.
     *
     * Menerima bentuk warisan textarea: JSON string, "label: nilai" per
     * baris, atau array objek/string. Record yang tidak relevan dibuang,
     * dan key yang tidak dimodelkan form dipertahankan.
     *
     * @param  list<string>  $keys
     * @return list<array<string, mixed>>
     */
    public static function normalizeRecordList(mixed $value, array $keys): array
    {
        if (is_string($value)) {
            $trimmed = trim($value);

            if ($trimmed === '') {
                return [];
            }

            if (str_starts_with($trimmed, '[') || str_starts_with($trimmed, '{')) {
                $decoded = json_decode($trimmed, true);

                if (is_array($decoded)) {
                    return self::normalizeRecordList($decoded, $keys);
                }
            }

            return self::recordsFromText($trimmed, $keys);
        }

        if (! is_array($value)) {
            return [];
        }

        $records = [];

        foreach ($value as $item) {
            if (is_array($item)) {
                $record = [];

                foreach ($item as $key => $field) {
                    if (! is_string($key)) {
                        continue;
                    }

                    if (is_scalar($field) || $field === null) {
                        $record[$key] = $field === null ? null : (string) $field;
                    }
                }

                if ($record !== []) {
                    $records[] = $record;
                }

                continue;
            }

            if (is_string($item) && trim($item) !== '') {
                $records = array_merge($records, self::recordsFromText(trim($item), $keys));
            }
        }

        return $records;
    }

    /**
     * Ubah teks warisan "label: nilai" / satu nilai per baris menjadi record.
     *
     * @param  list<string>  $keys
     * @return list<array<string, mixed>>
     */
    private static function recordsFromText(string $text, array $keys): array
    {
        $records = [];

        foreach (preg_split('/\r?\n/', $text) ?: [] as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            $separator = str_contains($line, ':') ? ':' : '|';
            $parts = array_map('trim', explode($separator, $line));

            $record = [];
            foreach ($keys as $index => $key) {
                $record[$key] = $parts[$index] ?? '';
            }

            $records[] = $record;
        }

        return $records;
    }

    /**
     * Gabung data section lama dengan input Builder baru.
     *
     * Key yang tidak dimodelkan di form dipertahankan, tetapi list berurutan
     * (mis. images dari FileUpload multiple) dan daftar record dari Repeater
     * diganti utuh agar hapus/reorder benar-benar tersimpan.
     * array_replace_recursive mempertahankan sisa item lama berdasarkan
     * indeks numerik.
     *
     * @param  array<string, mixed>  $previous
     * @param  array<string, mixed>  $current
     * @return array<string, mixed>
     */
    private static function mergeSectionData(array $previous, array $current): array
    {
        $merged = $previous;

        foreach ($current as $key => $value) {
            if (is_array($value) && array_is_list($value)) {
                $merged[$key] = $value;

                continue;
            }

            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = self::isAssocList($value)
                    ? $value
                    : array_replace_recursive($merged[$key], $value);

                continue;
            }

            $merged[$key] = $value;
        }

        return $merged;
    }

    /**
     * Deteksi list record yang di-key UUID oleh Repeater Filament
     * (semua nilainya array, bukan scalar), sehingga bisa diganti utuh.
     *
     * @param  array<array-key, mixed>  $value
     */
    private static function isAssocList(array $value): bool
    {
        if ($value === [] || array_is_list($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (! is_array($item)) {
                return false;
            }
        }

        return true;
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
