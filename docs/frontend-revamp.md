# Frontend Revamp — Design System & Component Guide

Dokumen ini mendampingi PRD (`PRD.md`) dan mencatat implementasi frontend yang
sudah ada: token desain, komponen, alur data CMS, dan cara menambah section baru
(PRD §16 poin 10).

## 1. Design tokens

Token didefinisikan sekali di `resources/css/app.css` (`@theme`) dan wajib dipakai
semua komponen (PRD §4).

| Token | Nilai | Utility |
|---|---|---|
| `--color-primary` | `#176B45` | `bg-primary`, `text-primary` |
| `--color-primary-dark` | `#104B32` | `bg-primary-dark` |
| `--color-primary-light` | `#E7F3EB` | `bg-primary-light` |
| `--color-accent` | `#B9D9A8` | `bg-accent` |
| `--color-surface` | `#F7F9F6` | `bg-surface` |
| `--color-background` | `#FFFFFF` | `bg-background` |
| `--color-foreground` | `#17251D` | `text-foreground` |
| `--color-muted` | `#66736A` | `text-muted` |
| `--color-border` | `#E3E9E3` | `border-border` |
| `--color-gold` | `#C6A45A` | aksen prestasi saja |

Radius: `rounded-input` (8px), `rounded-button` (10px), `rounded-card` (14px),
`rounded-panel` (20px). Lebar konten: `.content-container` / `max-w-content`
(1280px). Tipografi: `font-display` (Plus Jakarta Sans) untuk heading,
`font-sans` (Inter) untuk body.

Aturan gerak & aksesibilitas: `prefers-reduced-motion` dihormati, focus ring
memakai `outline-primary`, kontras mengikuti WCAG 2.2 AA (PRD §10, §12).

## 2. Struktur folder

```
resources/js/
  components/
    layout/       AnnouncementBar, AppHeader, MobileNavigation, AppFooter, PageHero, SiteStructuredData
    ui/           BaseButton, BaseLink, SectionContainer, SectionHeading, PageContainer,
                  Breadcrumbs, Pagination, EmptyState, LoadingState, ErrorState,
                  AppIcon, AspectImage
    content/      NewsCard, ProgramCard, AchievementItem, FacilityCard, StatCard,
                  ValueCard, TeacherCard, ImageGallery
    sections/     satu komponen per tipe section page builder
    page-builder/ PageRenderer, SectionRegistry, SectionFallback
  composables/    useSite, useSectionData
  lib/            data (parsing CMS), format (tanggal/URL), homeSections (komposisi default)
  pages/          Home, NewsIndex/Detail, DepartmentIndex/Detail, AnnouncementIndex/Detail,
                  PageDetail, Error
```

## 3. Alur data

Data dibagikan lewat Inertia (bukan request API berulang, PRD §11):

- `navigation` — menu header/footer (`AppServiceProvider`).
- `siteSettings` — identitas sekolah (`SchoolSetting`).
- `announcementBar` — pengumuman penting terpilih (`SiteContext::announcementBar`).
- `admissions` — status SPMB turunan (`SiteContext::admissions`).
- `pageCollections` — departments, news, announcements, achievements, facilities,
  galleries, teachers, statistics (`SiteContext::collections`, cache 10 menit).

Akses di komponen hanya lewat `useSite()`; parsing data section lewat
`useSectionData(section)` (`text`, `number`, `flag`, `list`, `records`, `stats`,
`faq`, `cta`). Semua helper toleran terhadap data kosong/format tak dikenal
(PRD §8.3).

### Penurunan data tanpa perubahan skema

- **Announcement bar** tampil hanya jika ada pengumuman aktif dengan
  `is_pinned = true`; URL mengarah ke detail pengumuman. Dapat ditutup pengguna.
- **SPMB aktif** jika ada halaman CMS dengan slug `spmb`/`ppdb`/`pendaftaran`/
  `penerimaan-siswa-baru`, atau pengumuman aktif terkait pendaftaran. Bila tidak,
  CTA pendaftaran disembunyikan (PRD §5.13).

## 4. Page builder

1. Backend: tipe di `App\Support\PageSections::TYPES` + blok di
   `PageForm` (`app/Filament/Resources/Pages/Schemas/PageForm.php`).
2. Frontend: tambahkan entri di `SectionRegistry.ts` →
   `sectionRegistry[type] = Component`.

`PageRenderer` memetakan `type` ke komponen, mempertahankan urutan CMS, dan
menampilkan `SectionFallback` untuk tipe tak dikenal sehingga satu section rusak
tidak menggagalkan seluruh halaman (PRD §8.2, §8.3).

Tipe yang tersedia: `hero`, `statistics`, `principal`, `programs`, `values`,
`latest_news`, `news`, `achievements`, `facilities`, `gallery`, `image_gallery`,
`cta`, `rich_text`, `image`, `card_grid`, `teachers`, `announcements`, `contact`,
`faq`, `custom_html`.

Setiap section memakai `SectionContainer` (tone + padding) dan `SectionHeading`
agar tetap konsisten dengan token; section tanpa konten menyembunyikan dirinya
atau menampilkan `EmptyState`.

## 5. SEO, performa, aksesibilitas

- Metadata dinamis + Open Graph + canonical pada halaman detail; JSON-LD
  `EducationalOrganization`/`WebSite` global (`SiteStructuredData`).
- Gambar memakai `AspectImage` (rasio terkunci, `loading="lazy"`, `alt`), hero
  memakai `eager` + `fetchpriority="high"` untuk LCP (PRD §11).
- Navigasi keyboard: dropdown header, drawer mobile, lightbox galeri, FAQ
  memakai `<details>`, skip-link ke `#main-content`.
- Bahasa Indonesia untuk seluruh copy default; tanggal diformat `id-ID`
  (`lib/format.ts`).

## 6. Menjalankan pemeriksaan

```bash
npm run check        # format + lint
npm run types:check  # vue-tsc
npm run build        # bundel produksi
vendor/bin/pint --dirty
vendor/bin/phpstan analyse
php artisan test --compact
```
