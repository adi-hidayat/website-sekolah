<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Department;
use Database\Seeders\Concerns\ProvidesPlaceholderImages;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    use ProvidesPlaceholderImages;

    /**
     * Prestasi peserta didik dan sekolah (PRD §5.10).
     */
    public function run(): void
    {
        $achievements = [
            ['Medali Emas Olimpiade Sains Nasional Bidang Fisika', 'Sains', 'Nasional', 'Juara 1', 'mipa'],
            ['Medali Perak Olimpiade Sains Nasional Bidang Kimia', 'Sains', 'Nasional', 'Juara 2', 'mipa'],
            ['Juara 1 Musabaqah Tilawatil Qur’an Tingkat Provinsi', 'Keagamaan', 'Provinsi', 'Juara 1', 'ilmu-keagamaan'],
            ['Juara 2 Lomba Debat Bahasa Inggris Tingkat Kota', 'Akademik', 'Kota', 'Juara 2', 'bahasa-dan-budaya'],
            ['Juara 1 Turnamen Basket Antarsekolah', 'Olahraga', 'Kota', 'Juara 1', null],
            ['Finalis International Science Olympiad', 'Sains', 'Internasional', 'Finalis', 'riset-dan-olimpiade'],
            ['Juara 1 Lomba Robotik Tingkat Nasional', 'Teknologi', 'Nasional', 'Juara 1', 'informatika'],
            ['Juara 3 Lomba Karya Tulis Ilmiah Remaja', 'Riset', 'Provinsi', 'Juara 3', 'riset-dan-olimpiade'],
            ['Juara 1 Festival Seni Tari Tradisional', 'Seni', 'Provinsi', 'Juara 1', null],
            ['Juara 2 Lomba Cerdas Cermat Ekonomi', 'Akademik', 'Kota', 'Juara 2', 'ips'],
            ['Juara 1 Olimpiade Matematika Tingkat Kota', 'Sains', 'Kota', 'Juara 1', 'mipa'],
            ['Medali Perunggu Olimpiade Biologi Nasional', 'Sains', 'Nasional', 'Juara 3', 'mipa'],
            ['Juara 1 Lomba Tahfidz 10 Juz Tingkat Provinsi', 'Keagamaan', 'Provinsi', 'Juara 1', 'ilmu-keagamaan'],
            ['Juara 2 Lomba Pidato Bahasa Arab', 'Keagamaan', 'Kota', 'Juara 2', 'ilmu-keagamaan'],
            ['Juara 1 Lomba Desain Poster Digital', 'Teknologi', 'Kota', 'Juara 1', 'informatika'],
            ['Juara 2 Kompetisi Hackathon Pelajar', 'Teknologi', 'Nasional', 'Juara 2', 'informatika'],
            ['Juara 1 Lomba Cipta Puisi Tingkat Provinsi', 'Seni', 'Provinsi', 'Juara 1', 'bahasa-dan-budaya'],
            ['Juara 3 Turnamen Futsal Pelajar', 'Olahraga', 'Kota', 'Juara 3', null],
            ['Juara 1 Lomba Karya Inovasi Sosial', 'Riset', 'Provinsi', 'Juara 1', 'ips'],
            ['Juara 2 Olimpiade Geografi Tingkat Nasional', 'Sains', 'Nasional', 'Juara 2', 'ips'],
            ['Juara 1 Lomba Storytelling Bahasa Inggris', 'Akademik', 'Kota', 'Juara 1', 'bahasa-dan-budaya'],
            ['Penghargaan Sekolah Adiwiyata Tingkat Provinsi', 'Sekolah', 'Provinsi', 'Penghargaan', null],
            ['Juara 1 Kontes Robot Line Follower', 'Teknologi', 'Provinsi', 'Juara 1', 'informatika'],
            ['Juara 2 Lomba Paduan Suara Hymne Sekolah', 'Seni', 'Kota', 'Juara 2', null],
        ];

        foreach ($achievements as $index => [$title, $category, $level, $rank, $departmentSlug]) {
            $department = $departmentSlug !== null
                ? Department::where('slug', $departmentSlug)->first()
                : null;

            Achievement::updateOrCreate(
                ['slug' => 'prestasi-'.str($title)->slug()->limit(60, '')->toString()],
                [
                    'title' => $title,
                    'description' => 'Capaian '.$title.' yang diraih melalui pembinaan berkelanjutan dan dukungan sekolah.',
                    'category' => $category,
                    'level' => $level,
                    'rank' => $rank,
                    'achieved_at' => now()->subMonths($index % 24)->startOfMonth()->toDateString(),
                    'image' => $this->photo('prestasi-'.$index, 800, 600),
                    'department_id' => $department?->id,
                    'is_published' => true,
                ],
            );
        }

        Achievement::updateOrCreate(['slug' => 'draf-prestasi-juara-lomba-fotografi'], [
            'title' => 'Draf: Juara Lomba Fotografi Pelajar',
            'description' => 'Menunggu verifikasi dokumentasi.',
            'category' => 'Seni',
            'level' => 'Kota',
            'rank' => 'Juara 1',
            'achieved_at' => now()->toDateString(),
            'is_published' => false,
        ]);
    }
}
