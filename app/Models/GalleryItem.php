<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\GalleryItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $gallery_id
 * @property string $type
 * @property string $file_path
 * @property string|null $caption
 * @property int $sort_order
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class GalleryItem extends Model
{
    /** @use HasFactory<GalleryItemFactory> */
    use HasFactory;

    protected $fillable = ['gallery_id', 'type', 'file_path', 'caption', 'sort_order'];

    /** @return BelongsTo<Gallery, $this> */
    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }
}
