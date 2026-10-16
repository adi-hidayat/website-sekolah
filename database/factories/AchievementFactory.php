<?php

namespace Database\Factories;

use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Achievement>
 */
class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = ucfirst(rtrim(fake()->unique()->sentence(4), '.'));

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'description' => fake()->sentence(),
            'category' => fake()->randomElement(['Akademik', 'Olahraga', 'Seni', 'Keagamaan', 'Sains']),
            'level' => fake()->randomElement(['Kota', 'Provinsi', 'Nasional', 'Internasional']),
            'rank' => fake()->randomElement(['Juara 1', 'Juara 2', 'Juara 3', 'Harapan 1']),
            'achieved_at' => fake()->dateTimeBetween('-3 years', 'now'),
            'image' => null,
            'department_id' => null,
            'is_published' => true,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['is_published' => false]);
    }
}
