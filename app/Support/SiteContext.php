<?php

namespace App\Support;

use App\Models\Achievement;
use App\Models\Announcement;
use App\Models\Department;
use App\Models\Facility;
use App\Models\Gallery;
use App\Models\News;
use App\Models\Page;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use Illuminate\Support\Facades\Cache;

/**
 * Konteks situs publik: koleksi data, bar pengumuman, dan status SPMB yang
 * diturunkan dari data CMS yang sudah ada (tanpa perubahan skema).
 *
 * @see PRD.md 5.1, 5.4, 5.13
 */
class SiteContext
{
    private const CACHE_TTL = 600;

    /**
     * Koleksi data yang dipakai section page builder.
     *
     * @return array<string, array<int, mixed>>
     */
    public static function collections(): array
    {
        try {
            /** @var array<string, array<int, mixed>> $collections */
            $collections = Cache::remember('site-page-collections', self::CACHE_TTL, fn (): array => self::buildCollections());

            return $collections;
        } catch (\Throwable) {
            return self::emptyCollections();
        }
    }

    /**
     * Pengumuman penting untuk bar tipis di atas navbar (PRD 5.1).
     * Kosong bila tidak ada pengumuman penting yang aktif.
     *
     * @return array{id:int,title:string,url:string,is_pinned:bool}|null
     */
    public static function announcementBar(): ?array
    {
        try {
            $announcement = Announcement::active()
                ->where('is_pinned', true)
                ->latest()
                ->first();

            if (! $announcement) {
                return null;
            }

            return [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'url' => '/pengumuman/'.$announcement->slug,
                'is_pinned' => true,
            ];
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Status penerimaan siswa baru (PRD 5.2 & 5.13). Aktif bila ada halaman
     * pendaftaran publik atau pengumuman pendaftaran yang sedang berjalan.
     *
     * @return array{active:bool,label:string,url:string,period_text:string|null}
     */
    public static function admissions(): array
    {
        $fallback = [
            'active' => false,
            'label' => 'Informasi Pendaftaran',
            'url' => '/pengumuman',
            'period_text' => null,
        ];

        try {
            $page = Page::published()
                ->whereIn('slug', ['spmb', 'ppdb', 'pendaftaran', 'penerimaan-siswa-baru'])
                ->first();

            if ($page) {
                return [
                    'active' => true,
                    'label' => 'Daftar Sekarang',
                    'url' => '/'.$page->slug,
                    'period_text' => null,
                ];
            }

            $announcement = Announcement::active()
                ->where(function ($query) {
                    $query->where('title', 'like', '%pendaftaran%')
                        ->orWhere('title', 'like', '%spmb%')
                        ->orWhere('title', 'like', '%ppdb%')
                        ->orWhere('category', 'like', '%pendaftaran%');
                })
                ->latest()
                ->first();

            if ($announcement) {
                return [
                    'active' => true,
                    'label' => 'Daftar Sekarang',
                    'url' => '/pengumuman/'.$announcement->slug,
                    'period_text' => self::periodText($announcement),
                ];
            }
        } catch (\Throwable) {
            return $fallback;
        }

        return $fallback;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private static function buildCollections(): array
    {
        $settings = SchoolSetting::current();

        return [
            'departments' => Department::published()
                ->orderBy('sort_order')
                ->get()
                ->map(fn (Department $department): array => [
                    'id' => $department->id,
                    'name' => $department->name,
                    'code' => $department->code,
                    'slug' => $department->slug,
                    'short_description' => $department->short_description,
                    'description' => $department->description,
                    'thumbnail' => $department->thumbnail,
                    'banner' => $department->banner,
                    'competencies' => $department->competencies ?? [],
                ])->all(),
            'news' => News::published()
                ->with(['categories'])
                ->latest('published_at')
                ->limit(9)
                ->get()
                ->map(fn (News $news): array => [
                    'id' => $news->id,
                    'title' => $news->title,
                    'slug' => $news->slug,
                    'excerpt' => $news->excerpt,
                    'featured_image' => $news->featured_image,
                    'published_at' => $news->published_at?->toIso8601String(),
                    'categories' => $news->categories
                        ->map(fn ($category): array => [
                            'id' => $category->id,
                            'name' => $category->name,
                            'slug' => $category->slug,
                        ])->all(),
                ])->all(),
            'announcements' => Announcement::active()
                ->orderByDesc('is_pinned')
                ->latest()
                ->limit(6)
                ->get()
                ->map(fn (Announcement $announcement): array => [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'slug' => $announcement->slug,
                    'category' => $announcement->category,
                    'starts_at' => $announcement->starts_at?->toIso8601String(),
                    'ends_at' => $announcement->ends_at?->toIso8601String(),
                    'attachment' => $announcement->attachment,
                    'is_pinned' => $announcement->is_pinned,
                ])->all(),
            'achievements' => Achievement::published()
                ->latest('achieved_at')
                ->limit(8)
                ->get()
                ->map(fn (Achievement $achievement): array => [
                    'id' => $achievement->id,
                    'title' => $achievement->title,
                    'slug' => $achievement->slug,
                    'description' => $achievement->description,
                    'category' => $achievement->category,
                    'level' => $achievement->level,
                    'rank' => $achievement->rank,
                    'achieved_at' => $achievement->achieved_at?->toIso8601String(),
                    'image' => $achievement->image,
                ])->all(),
            'facilities' => Facility::active()
                ->orderBy('sort_order')
                ->limit(9)
                ->get()
                ->map(fn (Facility $facility): array => [
                    'id' => $facility->id,
                    'name' => $facility->name,
                    'slug' => $facility->slug,
                    'description' => $facility->description,
                    'image' => $facility->image,
                    'icon' => $facility->icon,
                ])->all(),
            'galleries' => Gallery::published()
                ->with('items')
                ->latest()
                ->limit(6)
                ->get()
                ->map(fn (Gallery $gallery): array => [
                    'id' => $gallery->id,
                    'title' => $gallery->title,
                    'slug' => $gallery->slug,
                    'description' => $gallery->description,
                    'cover_image' => $gallery->cover_image,
                    'items' => $gallery->items
                        ->map(fn ($item): array => [
                            'id' => $item->id,
                            'type' => $item->type,
                            'file_path' => $item->file_path,
                            'caption' => $item->caption,
                        ])->all(),
                ])->all(),
            'teachers' => Teacher::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit(12)
                ->get()
                ->map(fn (Teacher $teacher): array => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'position' => $teacher->position,
                    'subject' => $teacher->subject,
                    'photo' => $teacher->photo,
                    'bio' => $teacher->bio,
                ])->all(),
            'statistics' => self::statistics($settings),
        ];
    }

    /**
     * Statistik dari pengaturan sekolah, atau dihitung dari data valid.
     *
     * @return array<int, array{label:string,value:string}>
     */
    private static function statistics(SchoolSetting $settings): array
    {
        $configured = collect($settings->statistics ?? [])
            ->filter(fn ($item): bool => is_array($item) && (string) ($item['label'] ?? '') !== '')
            ->map(fn (array $item): array => [
                'label' => (string) $item['label'],
                'value' => (string) ($item['value'] ?? ''),
            ])
            ->values()
            ->all();

        if ($configured !== []) {
            return $configured;
        }

        $candidates = [
            ['label' => 'Tenaga Pendidik', 'value' => Teacher::query()->where('is_active', true)->count()],
            ['label' => 'Program Jurusan', 'value' => Department::published()->count()],
            ['label' => 'Prestasi', 'value' => Achievement::published()->count()],
            ['label' => 'Fasilitas', 'value' => Facility::active()->count()],
        ];

        return collect($candidates)
            ->filter(fn (array $item): bool => $item['value'] > 0)
            ->map(fn (array $item): array => [
                'label' => $item['label'],
                'value' => (string) $item['value'],
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private static function emptyCollections(): array
    {
        return [
            'departments' => [],
            'news' => [],
            'announcements' => [],
            'achievements' => [],
            'facilities' => [],
            'galleries' => [],
            'teachers' => [],
            'statistics' => [],
        ];
    }

    private static function periodText(Announcement $announcement): ?string
    {
        $start = $announcement->starts_at?->format('d/m/Y');
        $end = $announcement->ends_at?->format('d/m/Y');

        return match (true) {
            $start !== null && $end !== null => "{$start} – {$end}",
            $start !== null => "Mulai {$start}",
            $end !== null => "Sampai {$end}",
            default => null,
        };
    }
}
