<?php

use App\Enums\MenuItemType;
use App\Filament\Resources\MenuItems\MenuItemResource;
use App\Filament\Resources\MenuItems\Pages\CreateMenuItem;
use App\Models\Announcement;
use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\News;
use App\Models\User;
use App\Support\MenuResolver;
use Livewire\Livewire;

function menuItem(Menu $menu, string $label, array $attributes = [], ?int $parentId = null): MenuItem
{
    return MenuItem::create(array_merge([
        'menu_id' => $menu->id,
        'parent_id' => $parentId,
        'label' => $label,
        'type' => MenuItemType::CustomUrl->value,
        'target' => '_self',
        'is_visible' => true,
        'sort_order' => 1,
    ], $attributes));
}

it('resolves news items to the index or a published article', function () {
    $menu = Menu::create(['name' => 'Header', 'location' => 'news-urls']);
    $published = News::factory()->published()->create(['slug' => 'juara-osn']);
    $draft = News::factory()->create(['slug' => 'draft-berita']);

    $index = menuItem($menu, 'Berita', ['type' => MenuItemType::News->value]);
    $detail = menuItem($menu, 'Juara OSN', ['type' => MenuItemType::News->value, 'news_id' => $published->id]);
    $hidden = menuItem($menu, 'Draft', ['type' => MenuItemType::News->value, 'news_id' => $draft->id]);

    expect($index->fresh()->resolvedUrl())->toBe('/berita')
        ->and($detail->fresh()->resolvedUrl())->toBe('/berita/juara-osn')
        ->and($hidden->fresh()->resolvedUrl())->toBeNull();
});

it('resolves announcement items to the index or an active announcement', function () {
    $menu = Menu::create(['name' => 'Header', 'location' => 'announcement-urls']);
    $active = Announcement::factory()->active()->create(['slug' => 'spmb-dibuka']);
    $inactive = Announcement::factory()->create(['slug' => 'belum-terbit']);

    $index = menuItem($menu, 'Pengumuman', ['type' => MenuItemType::Announcement->value]);
    $detail = menuItem($menu, 'SPMB', ['type' => MenuItemType::Announcement->value, 'announcement_id' => $active->id]);
    $hidden = menuItem($menu, 'Belum terbit', ['type' => MenuItemType::Announcement->value, 'announcement_id' => $inactive->id]);

    expect($index->fresh()->resolvedUrl())->toBe('/pengumuman')
        ->and($detail->fresh()->resolvedUrl())->toBe('/pengumuman/spmb-dibuka')
        ->and($hidden->fresh()->resolvedUrl())->toBeNull();
});

it('resolves gallery items to the index or a published album', function () {
    $menu = Menu::create(['name' => 'Header', 'location' => 'gallery-urls']);
    $album = Gallery::factory()->create(['slug' => 'wisuda-tahfidz']);
    $draft = Gallery::factory()->draft()->create(['slug' => 'album-draft']);

    $index = menuItem($menu, 'Galeri', ['type' => MenuItemType::Gallery->value]);
    $detail = menuItem($menu, 'Wisuda', ['type' => MenuItemType::Gallery->value, 'gallery_id' => $album->id]);
    $hidden = menuItem($menu, 'Draft', ['type' => MenuItemType::Gallery->value, 'gallery_id' => $draft->id]);

    expect($index->fresh()->resolvedUrl())->toBe('/galeri')
        ->and($detail->fresh()->resolvedUrl())->toBe('/galeri/wisuda-tahfidz')
        ->and($hidden->fresh()->resolvedUrl())->toBeNull();
});

it('clears content references that do not match the item type', function () {
    $menu = Menu::create(['name' => 'Header', 'location' => 'normalize']);
    $news = News::factory()->published()->create();
    $gallery = Gallery::factory()->create();

    $item = menuItem($menu, 'Berita', [
        'type' => MenuItemType::News->value,
        'news_id' => $news->id,
        'gallery_id' => $gallery->id,
    ]);

    expect($item->news_id)->toBe($news->id)
        ->and($item->gallery_id)->toBeNull();

    $item->update(['type' => MenuItemType::Gallery->value, 'gallery_id' => $gallery->id]);

    expect($item->fresh()->news_id)->toBeNull()
        ->and($item->fresh()->gallery_id)->toBe($gallery->id);
});

it('drops invalid items from the resolved tree without breaking navigation', function () {
    $menu = Menu::create(['name' => 'Header', 'location' => 'tree']);
    menuItem($menu, 'Berita', ['type' => MenuItemType::News->value, 'sort_order' => 1]);
    menuItem($menu, 'Galeri', ['type' => MenuItemType::Gallery->value, 'sort_order' => 2]);
    menuItem($menu, 'Draft Album', [
        'type' => MenuItemType::Gallery->value,
        'gallery_id' => Gallery::factory()->draft()->create()->id,
        'sort_order' => 3,
    ]);

    $tree = MenuResolver::tree($menu->items()->with(['page', 'department', 'news', 'announcement', 'gallery'])->get());

    expect($tree)->toHaveCount(2)
        ->and($tree[0]['url'])->toBe('/berita')
        ->and($tree[1]['url'])->toBe('/galeri');
});

it('renders the menu item resource pages', function () {
    $this->actingAs(User::factory()->create());

    $menu = Menu::create(['name' => 'Header', 'location' => 'filament-menu']);
    $item = menuItem($menu, 'Berita', ['type' => MenuItemType::News->value]);

    $this->get(MenuItemResource::getUrl('index'))->assertOk();
    $this->get(MenuItemResource::getUrl('create'))->assertOk();
    $this->get(MenuItemResource::getUrl('edit', ['record' => $item]))->assertOk();
});

it('creates a gallery menu item through the admin form', function () {
    $this->actingAs(User::factory()->create());

    $menu = Menu::create(['name' => 'Header', 'location' => 'create-menu']);
    $gallery = Gallery::factory()->create(['slug' => 'wisuda-tahfidz']);
    GalleryItem::factory()->create(['gallery_id' => $gallery->id]);

    Livewire::test(CreateMenuItem::class)
        ->fillForm([
            'menu_id' => $menu->id,
            'label' => 'Album Wisuda',
            'type' => MenuItemType::Gallery->value,
            'gallery_id' => $gallery->id,
            'target' => '_self',
            'is_visible' => true,
            'sort_order' => 3,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $item = MenuItem::where('label', 'Album Wisuda')->first();

    expect($item)->not->toBeNull()
        ->and($item->type)->toBe(MenuItemType::Gallery)
        ->and($item->gallery_id)->toBe($gallery->id)
        ->and($item->resolvedUrl())->toBe('/galeri/wisuda-tahfidz');
});
