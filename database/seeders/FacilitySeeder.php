<?php

namespace Database\Seeders;

use App\Models\Facility;
use Database\Seeders\Concerns\ProvidesPlaceholderImages;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    use ProvidesPlaceholderImages;

    /**
     * Fasilitas sekolah (PRD §5.11).
     */
    public function run(): void
    {
        $facilities = [
            ['Ruang Kelas Berpendingin', 'building', 'Ruang kelas nyaman dengan pendingin, proyektor, dan pencahayaan yang memadai.'],
            ['Laboratorium IPA', 'beaker', 'Laboratorium fisika, kimia, dan biologi untuk pembelajaran praktikum.'],
            ['Laboratorium Komputer', 'building', 'Komputer modern untuk pembelajaran informatika dan literasi digital.'],
            ['Perpustakaan Digital', 'book', 'Koleksi buku cetak dan akses pustaka digital untuk mendukung literasi.'],
            ['Masjid Sekolah', 'users', 'Pusat kegiatan ibadah, kajian, dan pembinaan karakter Islami.'],
            ['Lapangan Olahraga Terpadu', 'trophy', 'Lapangan basket, futsal, dan voli untuk kegiatan olahraga dan ekstrakurikuler.'],
            ['Aula Serbaguna', 'sparkles', 'Aula untuk kegiatan seminar, pentas seni, dan pertemuan warga sekolah.'],
            ['Ruang Multimedia', 'globe', 'Studio untuk produksi konten pembelajaran dan kegiatan jurnalistik siswa.'],
            ['Ruang Bimbingan Konseling', 'heart', 'Ruang konsultasi yang nyaman untuk pendampingan peserta didik.'],
            ['Kantin Sehat', 'clock', 'Kantin dengan pilihan makanan sehat dan higienis.'],
        ];

        foreach ($facilities as $index => [$name, $icon, $description]) {
            Facility::updateOrCreate(
                ['slug' => str($name)->slug()->toString()],
                [
                    'name' => $name,
                    'description' => $description,
                    'image' => $this->photo('fasilitas-'.str($name)->slug()->toString(), 1200, 900),
                    'icon' => $icon,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
