<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\AchievementFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $description
 * @property string|null $category
 * @property string|null $level
 * @property string|null $rank
 * @property CarbonImmutable|null $achieved_at
 * @property string|null $image
 * @property int|null $department_id
 * @property bool $is_published
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class Achievement extends Model
{
    /** @use HasFactory<AchievementFactory> */
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'category', 'level', 'rank',
        'achieved_at', 'image', 'department_id', 'is_published',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['achieved_at' => 'date', 'is_published' => 'boolean'];
    }

    /** @return BelongsTo<Department, $this> */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @param  Builder<Achievement>  $query
     * @return Builder<Achievement>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
