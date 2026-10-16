<?php

use App\Models\Announcement;
use App\Models\Department;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Page;
use App\Models\Tag;

uses()->group('api')->in(__DIR__);

it('returns site settings', function () {
    $response = $this->getJson('/api/v1/site-settings');

    $response->assertOk()->assertJsonStructure(['data' => ['school_name', 'address', 'phone']]);
});

it('returns menus and hides invalid items without breaking navigation', function () {
    $menu = Menu::create(['name' => 'Header', 'location' => 'header-test']);
    $page = Page::factory()->published()->create();
    $draft = Page::factory()->create(['status' => 'draft']);

    MenuItem::create(['menu_id' => $menu->id, 'label' => 'Valid', 'type' => 'page', 'page_id' => $page->id, 'target' => '_self', 'is_visible' => true, 'sort_order' => 1]);
    MenuItem::create(['menu_id' => $menu->id, 'label' => 'Draft', 'type' => 'page', 'page_id' => $draft->id, 'target' => '_self', 'is_visible' => true, 'sort_order' => 2]);
    MenuItem::create(['menu_id' => $menu->id, 'label' => 'Broken', 'type' => 'custom_url', 'target_url' => 'not-a-url', 'target' => '_self', 'is_visible' => true, 'sort_order' => 3]);

    $response = $this->getJson('/api/v1/menus/header-test');

    $response->assertOk();
    $labels = collect($response->json('data'))->pluck('label');
    expect($labels)->toContain('Valid')->not->toContain('Draft')->not->toContain('Broken');
});

it('serves only published pages and handles slug redirects', function () {
    $page = Page::factory()->published()->create(['slug' => 'profil-sekolah']);
    $draft = Page::factory()->create(['slug' => 'rahasia', 'status' => 'draft']);

    $this->getJson('/api/v1/pages/profil-sekolah')->assertOk()->assertJsonPath('data.slug', 'profil-sekolah');
    $this->getJson('/api/v1/pages/rahasia')->assertNotFound();

    $page->update(['slug' => 'profil-baru']);
    $this->getJson('/api/v1/pages/profil-sekolah')->assertStatus(301)->assertJsonPath('redirect_to', '/profil-baru');
});

it('lists and shows published departments only', function () {
    Department::factory()->published()->create(['slug' => 'ipa-test']);
    Department::factory()->create(['slug' => 'draft-test', 'status' => 'draft']);

    $this->getJson('/api/v1/departments')->assertOk()->assertJsonFragment(['slug' => 'ipa-test'])->assertJsonMissing(['slug' => 'draft-test']);
    $this->getJson('/api/v1/departments/ipa-test')->assertOk();
    $this->getJson('/api/v1/departments/draft-test')->assertNotFound();
});

it('supports news search, filter, pagination and related', function () {
    $cat = NewsCategory::create(['name' => 'Kegiatan', 'slug' => 'kegiatan-test']);
    $tag = Tag::create(['name' => 'Lomba', 'slug' => 'lomba-test']);
    $news = News::factory()->published()->create(['title' => 'Juara Olimpiade Sains', 'slug' => 'juara-test']);
    $news->categories()->attach($cat->id);
    $news->tags()->attach($tag->id);
    News::factory()->published()->create(['title' => 'Kegiatan Pramuka', 'slug' => 'pramuka-test']);

    $this->getJson('/api/v1/news?search=Olimpiade')->assertOk()->assertJsonFragment(['slug' => 'juara-test'])->assertJsonMissing(['slug' => 'pramuka-test']);
    $this->getJson('/api/v1/news?category=kegiatan-test')->assertOk()->assertJsonFragment(['slug' => 'juara-test']);
    $this->getJson('/api/v1/news?per_page=1')->assertOk()->assertJsonPath('meta.per_page', 1);
    $this->getJson('/api/v1/news/juara-test')->assertOk()->assertJsonStructure(['data' => ['title', 'content'], 'meta' => ['related']]);
    $this->getJson('/api/v1/news-categories')->assertOk();
});

it('lists only active announcements', function () {
    Announcement::factory()->active()->create(['slug' => 'aktif-test']);
    Announcement::factory()->create(['slug' => 'draft-pengumuman', 'status' => 'draft']);
    Announcement::factory()->create([
        'slug' => 'kedaluwarsa-test', 'status' => 'published',
        'starts_at' => now()->subMonth()->toDateString(), 'ends_at' => now()->subWeek()->toDateString(),
    ]);

    $response = $this->getJson('/api/v1/announcements');
    $response->assertOk();
    $slugs = collect($response->json('data'))->pluck('slug');
    expect($slugs)->toContain('aktif-test')->not->toContain('draft-pengumuman')->not->toContain('kedaluwarsa-test');

    $this->getJson('/api/v1/announcements/aktif-test')->assertOk();
});
