<?php

namespace App\Models;

use App\Enums\MenuItemType;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $menu_id
 * @property int|null $parent_id
 * @property string $label
 * @property MenuItemType $type
 * @property int|null $page_id
 * @property int|null $department_id
 * @property string|null $target_url
 * @property string $target
 * @property bool $is_visible
 * @property int $sort_order
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class MenuItem extends Model
{
    protected $fillable = [
        'menu_id', 'parent_id', 'label', 'type', 'page_id',
        'department_id', 'target_url', 'target', 'is_visible', 'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => MenuItemType::class,
            'is_visible' => 'boolean',
        ];
    }

    /** @return BelongsTo<Menu, $this> */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /** @return BelongsTo<MenuItem, $this> */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /** @return HasMany<MenuItem, $this> */
    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('sort_order');
    }

    /** @return BelongsTo<Page, $this> */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Resolve URL publik. Null = tidak valid / tidak aktif (PRD 6.5).
     */
    public function resolvedUrl(): ?string
    {
        return match ($this->type) {
            MenuItemType::Page => $this->page && $this->page->isPublished()
                ? '/'.$this->page->slug : null,
            MenuItemType::Department => $this->department && $this->department->isPublished()
                ? '/jurusan/'.$this->department->slug : null,
            MenuItemType::News => '/berita',
            MenuItemType::Announcement => '/pengumuman',
            MenuItemType::CustomUrl => $this->validCustomUrl(),
            MenuItemType::Dropdown => null,
        };
    }

    protected function validCustomUrl(): ?string
    {
        $url = trim((string) $this->target_url);
        if ($url === '') {
            return null;
        }

        if (str_starts_with($url, '/')) {
            return $url;
        }

        if (filter_var($url, FILTER_VALIDATE_URL) && preg_match('#^https?://#i', $url)) {
            return $url;
        }

        return null;
    }

    public function isExternal(): bool
    {
        $url = $this->resolvedUrl();

        return $url !== null && (bool) preg_match('#^https?://#i', $url);
    }
}
