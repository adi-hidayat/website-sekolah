<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $school_name
 * @property string|null $short_name
 * @property string|null $npsn
 * @property string|null $accreditation
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $website
 * @property string|null $logo
 * @property string|null $principal_name
 * @property string|null $principal_welcome
 * @property string|null $principal_photo
 * @property string|null $vision
 * @property string|null $mission
 * @property string|null $history
 * @property string|null $maps_embed_url
 * @property array<string, mixed>|null $social_links
 * @property array<int, mixed>|null $statistics
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class SchoolSetting extends Model
{
    protected $fillable = [
        'school_name', 'short_name', 'npsn', 'accreditation', 'address',
        'phone', 'email', 'website', 'logo', 'principal_name',
        'principal_welcome', 'principal_photo', 'vision', 'mission',
        'history', 'maps_embed_url', 'social_links', 'statistics',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['social_links' => 'array', 'statistics' => 'array'];
    }

    public static function current(): self
    {
        return static::query()->first() ?? new self(['school_name' => 'MAS AL-MAHFUZHIYAH KALIDADI']);
    }
}
