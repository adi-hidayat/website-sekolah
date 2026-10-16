<?php

namespace App\Models;

use App\Enums\PageStatus;
use App\Enums\PageTemplate;
use App\Support\PageSections;
use Carbon\CarbonImmutable;
use Database\Factories\PageFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string|null $content
 * @property array<string, mixed>|null $sections
 * @property string $template
 * @property string|null $featured_image
 * @property PageStatus $status
 * @property CarbonImmutable|null $published_at
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $canonical_url
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 */
class Page extends Model
{
    /** @use HasFactory<PageFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'sections', 'template',
        'featured_image', 'status', 'published_at', 'seo_title',
        'seo_description', 'canonical_url', 'created_by', 'updated_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'status' => PageStatus::class,
            'published_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return BelongsTo<User, $this> */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @param  Builder<Page>  $query
     * @return Builder<Page>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', PageStatus::Published)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function isPublished(): bool
    {
        return $this->status === PageStatus::Published
            && ($this->published_at === null || $this->published_at->isPast());
    }

    /**
     * @return array{version:int, sections:array<int, array{id:string, type:string, data:array<string, mixed>}>}
     */
    public function normalizedSections(): array
    {
        return PageSections::normalize($this->sections);
    }

    public function seoTitle(): string
    {
        return $this->seo_title ?: $this->title;
    }

    public function templateEnum(): PageTemplate
    {
        return PageTemplate::tryFrom($this->template) ?? PageTemplate::Default;
    }
}
