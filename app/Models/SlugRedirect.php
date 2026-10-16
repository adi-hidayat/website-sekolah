<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $old_slug
 * @property string $new_slug
 * @property string $content_type
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class SlugRedirect extends Model
{
    protected $fillable = ['old_slug', 'new_slug', 'content_type'];
}
