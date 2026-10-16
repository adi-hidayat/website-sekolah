<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Konten sekolah: pengaturan, jurusan, guru, halaman, menu, berita,
     * pengumuman, prestasi, fasilitas, dan galeri (PRD §5-§6).
     */
    public function run(): void
    {
        $this->call([
            SchoolSettingSeeder::class,
            DepartmentSeeder::class,
            TeacherSeeder::class,
            PageSeeder::class,
            MenuSeeder::class,
            NewsSeeder::class,
            AnnouncementSeeder::class,
            AchievementSeeder::class,
            FacilitySeeder::class,
            GallerySeeder::class,
        ]);
    }
}
