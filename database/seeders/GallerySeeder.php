<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\GalleryItem;
use Database\Seeders\Concerns\ProvidesPlaceholderImages;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    use ProvidesPlaceholderImages;

    /**
     * Album galeri kegiatan (PRD §5.12).
     */
    public function run(): void
    {
        $albums = [
            ['Kegiatan Belajar Mengajar', 'Suasana pembelajaran di kelas, laboratorium, dan perpustakaan.'],
            ['Pesantren Ramadan', 'Rangkaian kegiatan pesantren Ramadan peserta didik.'],
            ['Wisuda Tahfidz', 'Prosesi wisuda hafalan Al-Qur’an angkatan terbaru.'],
            ['Studi Kampus', 'Kunjungan studi ke perguruan tinggi dan industri.'],
            ['Pekan Seni dan Budaya', 'Panggung seni, pameran karya, dan pentas budaya siswa.'],
            ['Kompetisi Robotik', 'Persiapan dan penampilan tim robotik sekolah.'],
            ['Bakti Sosial', 'Kegiatan sosial dan kepedulian peserta didik.'],
            ['Ekstrakurikuler Pramuka', 'Kegiatan kepramukaan dan pembinaan kepemimpinan.'],
        ];

        foreach ($albums as [$title, $description]) {
            $slug = str($title)->slug()->toString();

            $gallery = Gallery::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'description' => $description,
                    'cover_image' => $this->photo('galeri-'.$slug, 1600, 900),
                    'is_published' => true,
                ],
            );

            for ($item = 1; $item <= 6; $item++) {
                GalleryItem::updateOrCreate(
                    ['gallery_id' => $gallery->id, 'sort_order' => $item],
                    [
                        'type' => 'photo',
                        'file_path' => $this->photo('galeri-'.$slug.'-'.$item, 1200, 800),
                        'caption' => $title.' — dokumentasi '.$item,
                    ],
                );
            }
        }
    }
}
