<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    protected $model = GalleryItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'gallery_id' => Gallery::factory(),
            'type' => 'photo',
            'file_path' => 'https://picsum.photos/seed/'.fake()->unique()->slug(2).'/1200/800',
            'caption' => fake()->sentence(4),
            'sort_order' => 0,
        ];
    }
}
