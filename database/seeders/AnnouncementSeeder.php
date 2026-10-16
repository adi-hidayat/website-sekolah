<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Pengumuman resmi sekolah (PRD §5.1, §6.5).
     */
    public function run(): void
    {
        $author = User::where('email', 'admin@example.com')->first()
            ?? User::query()->first();

        $announcements = [
            ['Penerimaan Peserta Didik Baru 2026/2027 Resmi Dibuka', 'Pendaftaran', 12, true],
            ['Jadwal Penilaian Tengah Semester Ganjil', 'Akademik', 9, false],
            ['Libur Semester Ganjil dan Awal Semester Genap', 'Akademik', 20, false],
            ['Pendaftaran Ekstrakurikuler Semester Genap', 'Kesiswaan', 14, false],
            ['Peringatan Isra Mikraj 1447 H', 'Keagamaan', 7, true],
            ['Pengambilan Rapor Semester Ganjil', 'Akademik', 10, false],
            ['Pekan Literasi dan Bazar Buku Sekolah', 'Kegiatan', 6, false],
            ['Kunjungan Orang Tua dan Konsultasi Wali Kelas', 'Kesiswaan', 5, false],
            ['Program Pertukaran Pelajar: Pendaftaran Seleksi', 'Akademik', 16, false],
            ['Jadwal Vaksinasi dan Pemeriksaan Kesehatan', 'Kesehatan', 4, false],
            ['Pengumuman Beasiswa Prestasi Yayasan', 'Beasiswa', 11, true],
            ['Perubahan Jam Kegiatan Belajar Ramadan', 'Keagamaan', 8, false],
        ];

        foreach ($announcements as $index => [$title, $category, $activeDays, $pinned]) {
            Announcement::updateOrCreate(
                ['slug' => 'pengumuman-'.str($title)->slug()->limit(60, '')->toString()],
                [
                    'title' => $title,
                    'content' => '<p>Diberitahukan kepada seluruh peserta didik, orang tua/wali, dan tenaga pendidik SMA Islam Nusantara bahwa '.$title.'.</p><p>Kami mohon perhatian dan kerja samanya agar seluruh rangkaian kegiatan dapat berjalan dengan baik.</p><ul><li>Informasi detail dapat ditanyakan kepada wali kelas atau bagian administrasi.</li><li>Perubahan jadwal akan diinformasikan melalui kanal resmi sekolah.</li></ul>',
                    'category' => $category,
                    'starts_at' => now()->subDays(2)->toDateString(),
                    'ends_at' => now()->addDays($activeDays)->toDateString(),
                    'status' => 'published',
                    'is_pinned' => $pinned,
                    'sort_order' => $index + 1,
                    'created_by' => $author?->id,
                ],
            );
        }

        // Pengumuman kedaluwarsa dan draf untuk menguji penyaringan status.
        Announcement::updateOrCreate(['slug' => 'pengumuman-jadwal-ujian-semester-lalu'], [
            'title' => 'Jadwal Ujian Semester Gasal 2025/2026',
            'content' => '<p>Jadwal ujian semester gasal tahun ajaran lalu.</p>',
            'category' => 'Akademik',
            'starts_at' => now()->subMonths(4)->toDateString(),
            'ends_at' => now()->subMonths(3)->toDateString(),
            'status' => 'published',
            'is_pinned' => false,
            'created_by' => $author?->id,
        ]);

        Announcement::updateOrCreate(['slug' => 'draf-pengumuman-reuni-akbar'], [
            'title' => 'Draf: Reuni Akbar Alumni SMA Islam Nusantara',
            'content' => '<p>Draf pengumuman yang belum dipublikasikan.</p>',
            'category' => 'Kegiatan',
            'status' => 'draft',
            'is_pinned' => false,
            'created_by' => $author?->id,
        ]);
    }
}
