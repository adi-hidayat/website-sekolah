<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\NewsCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class NewsCategory extends Model
{
    /** @use HasFactory<NewsCategoryFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    /** @return BelongsToMany<News, $this> */
    public function news(): BelongsToMany
    {
        return $this->belongsToMany(News::class, 'category_news');
    }
}
