<?php

namespace Database\Factories;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Announcement> */
class AnnouncementFactory extends Factory
{
    protected $model = Announcement::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'content' => '<p>'.fake()->paragraph().'</p>',
            'status' => 'draft',
            'is_pinned' => false,
            'sort_order' => 0,
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => [
            'status' => 'published',
            'starts_at' => now()->subDay()->toDateString(),
            'ends_at' => now()->addWeek()->toDateString(),
        ]);
    }
}
