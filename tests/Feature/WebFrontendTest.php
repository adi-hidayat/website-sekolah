<?php

use App\Models\Announcement;
use App\Models\Department;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Page;
use App\Support\PageSections;
use Inertia\Testing\AssertableInertia;

it('renders the homepage with shared site context', function () {
    Department::factory()->published()->create(['slug' => 'ipa-shared']);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('admissions')
            ->has('pageCollections.departments', 1)
            ->has('pageCollections.statistics')
            ->has('navigation')
            ->has('siteSettings'));
});

it('exposes a pinned announcement through the announcement bar only', function () {
    Announcement::factory()->active()->create(['slug' => 'pinned-news', 'is_pinned' => true]);
    Announcement::factory()->active()->create(['slug' => 'regular-news', 'is_pinned' => false]);

    $this->get(route('home'))->assertInertia(fn (AssertableInertia $page) => $page
        ->where('announcementBar.url', '/pengumuman/pinned-news'));
});

it('hides the announcement bar when nothing is pinned', function () {
    Announcement::factory()->active()->create(['is_pinned' => false]);

    $this->get(route('home'))->assertInertia(fn (AssertableInertia $page) => $page
        ->where('announcementBar', null));
});

it('marks admissions inactive by default and active when a registration page exists', function () {
    $this->get(route('home'))->assertInertia(fn (AssertableInertia $page) => $page
        ->where('admissions.active', false));

    Page::factory()->published()->create(['slug' => 'spmb', 'title' => 'SPMB']);

    $this->get(route('home'))->assertInertia(fn (AssertableInertia $page) => $page
        ->where('admissions.active', true)
        ->where('admissions.url', '/spmb'));
});

it('keeps the new page builder section types when normalizing', function () {
    $types = ['principal', 'values', 'achievements', 'facilities', 'gallery', 'news'];

    $sections = collect($types)
        ->map(fn (string $type, int $index): array => ['id' => "s-{$index}", 'type' => $type, 'data' => []])
        ->all();

    expect(PageSections::normalize($sections)['sections'])->toHaveCount(count($types));
});

it('filters news by category and exposes the category list', function () {
    $category = NewsCategory::create(['name' => 'Kegiatan', 'slug' => 'kegiatan']);
    $news = News::factory()->published()->create(['slug' => 'berita-kegiatan']);
    $news->categories()->attach($category->id);

    News::factory()->published()->create(['slug' => 'berita-lain']);

    $this->get(route('news.index', ['category' => 'kegiatan']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('NewsIndex')
            ->has('categories', 1)
            ->where('filters.category', 'kegiatan')
            ->has('news.data', 1));
});

it('renders an inertian error page for unknown slugs', function () {
    $this->get('/halaman-tidak-ada')
        ->assertNotFound()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Error')
            ->where('status', 404));
});

it('renders a published cms page with its sections', function () {
    Page::factory()->published()->create([
        'slug' => 'profil-sekolah',
        'title' => 'Profil Sekolah',
        'sections' => [
            'version' => 1,
            'sections' => [
                ['id' => 'principal-1', 'type' => 'principal', 'data' => []],
            ],
        ],
    ]);

    $this->get('/profil-sekolah')
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PageDetail')
            ->where('page.slug', 'profil-sekolah')
            ->has('page.sections', 1)
            ->where('page.sections.0.type', 'principal'));
});

it('renders the gallery index and detail pages', function () {
    $gallery = Gallery::factory()->create(['slug' => 'wisuda-tahfidz']);
    GalleryItem::factory()->count(2)->create(['gallery_id' => $gallery->id]);

    $this->get(route('galleries.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('GalleryIndex')
            ->has('galleries.data', 1)
            ->where('galleries.data.0.items_count', 2));

    $this->get(route('galleries.show', ['slug' => 'wisuda-tahfidz']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('GalleryDetail')
            ->where('gallery.slug', 'wisuda-tahfidz')
            ->has('gallery.items', 2));
});

it('hides unpublished galleries from public routes', function () {
    Gallery::factory()->draft()->create(['slug' => 'album-draft']);

    $this->get(route('galleries.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('galleries.data', 0));

    $this->get(route('galleries.show', ['slug' => 'album-draft']))->assertNotFound();
});
