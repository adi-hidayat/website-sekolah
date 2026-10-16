<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    protected $model = Gallery::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = ucfirst(rtrim(fake()->unique()->sentence(3), '.'));

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'description' => fake()->sentence(),
            'cover_image' => null,
            'is_published' => true,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['is_published' => false]);
    }
}
