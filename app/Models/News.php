<?php

namespace App\Models;

use App\Enums\NewsStatus;
use Carbon\CarbonImmutable;
use Database\Factories\NewsFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $excerpt
 * @property string $content
 * @property string|null $featured_image
 * @property array<string, mixed>|null $gallery
 * @property int|null $author_id
 * @property CarbonImmutable|null $published_at
 * @property NewsStatus $status
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $canonical_url
 * @property int $views_count
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 */
class News extends Model
{
    /** @use HasFactory<NewsFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image', 'gallery',
        'author_id', 'published_at', 'status', 'seo_title',
        'seo_description', 'canonical_url', 'views_count',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'published_at' => 'datetime',
            'status' => NewsStatus::class,
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /** @return BelongsToMany<NewsCategory, $this> */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(NewsCategory::class, 'category_news');
    }

    /** @return BelongsToMany<Tag, $this> */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'news_tag');
    }

    /**
     * @param  Builder<News>  $query
     * @return Builder<News>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', NewsStatus::Published)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    /** @return Collection<int, News> */
    public function related(int $limit = 3): Collection
    {
        return self::published()
            ->where('id', '!=', $this->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('news_categories.id', $this->categories->pluck('id')))
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }
}
