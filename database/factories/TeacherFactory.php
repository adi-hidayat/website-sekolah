<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = fake('id_ID');

        return [
            'name' => $faker->name(),
            'nip' => $faker->numerify('19##############'),
            'position' => $faker->randomElement(['Guru', 'Wali Kelas', 'Guru Bimbingan Konseling']),
            'subject' => $faker->randomElement([
                'Matematika', 'Fisika', 'Kimia', 'Biologi', 'Bahasa Indonesia',
                'Bahasa Inggris', 'Sejarah', 'Ekonomi', 'Geografi', 'Sosiologi',
                'Pendidikan Agama Islam', 'Informatika', 'Pendidikan Jasmani',
            ]),
            'photo' => null,
            'bio' => $faker->sentence(),
            'department_id' => null,
            'sort_order' => 0,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
