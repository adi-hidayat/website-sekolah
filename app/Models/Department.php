<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\DepartmentFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $name
 * @property string|null $code
 * @property string $slug
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $thumbnail
 * @property string|null $banner
 * @property list<string>|null $competencies
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string $status
 * @property int $sort_order
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 */
class Department extends Model implements HasMedia
{
    /** @use HasFactory<DepartmentFactory> */
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'slug', 'short_description', 'description',
        'thumbnail', 'banner', 'competencies', 'seo_title',
        'seo_description', 'status', 'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['competencies' => 'array'];
    }

    /** @return HasMany<Teacher, $this> */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    /** @return HasMany<Achievement, $this> */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * @param  Builder<Department>  $query
     * @return Builder<Department>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }
}
