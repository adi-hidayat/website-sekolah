<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\NewsCategory;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\Concerns\ProvidesPlaceholderImages;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    use ProvidesPlaceholderImages;

    /**
     * Kategori, tag, dan berita sekolah (PRD §5.9, §6.3-6.4).
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Kegiatan Sekolah', 'slug' => 'kegiatan-sekolah', 'description' => 'Agenda dan kegiatan resmi sekolah.'],
            ['name' => 'Prestasi', 'slug' => 'prestasi', 'description' => 'Capaian peserta didik dan sekolah.'],
            ['name' => 'Keislaman', 'slug' => 'keislaman', 'description' => 'Kegiatan pembinaan keislaman dan tahfidz.'],
            ['name' => 'Akademik', 'slug' => 'akademik', 'description' => 'Program pembelajaran dan akademik.'],
            ['name' => 'Ekstrakurikuler', 'slug' => 'ekstrakurikuler', 'description' => 'Kegiatan pengembangan minat dan bakat.'],
            ['name' => 'Pengumuman', 'slug' => 'pengumuman', 'description' => 'Informasi resmi untuk warga sekolah.'],
        ];

        foreach ($categories as $category) {
            NewsCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }

        $tagNames = ['Olimpiade', 'Tahfidz', 'Pramuka', 'Robotik', 'Literasi', 'Riset', 'Seni', 'Olahraga', 'Bakti Sosial', 'Sains', 'Bahasa', 'Kepemimpinan'];

        foreach ($tagNames as $name) {
            Tag::firstOrCreate(['name' => $name], ['slug' => str($name)->slug()->toString()]);
        }

        $author = User::where('email', 'admin@example.com')->first()
            ?? User::query()->first();

        $categoryIds = NewsCategory::pluck('id', 'slug');
        $tagIds = Tag::pluck('id', 'name');

        $articles = [
            ['Pembukaan Tahun Ajaran Baru 2026/2027 Berlangsung Khidmat', 'kegiatan-sekolah', ['Kepemimpinan']],
            ['Pesantren Ramadan 1447 H Diikuti 400 Peserta Didik', 'keislaman', ['Tahfidz', 'Kepemimpinan']],
            ['Siswa SMA Islam Nusantara Raih Medali Emas OSN Fisika', 'prestasi', ['Olimpiade', 'Sains']],
            ['Program Literasi Sains Ditingkatkan di Semester Ganjil', 'akademik', ['Literasi', 'Sains']],
            ['Ekstrakurikuler Robotik Siap Ikuti Kompetisi Nasional', 'ekstrakurikuler', ['Robotik', 'Sains']],
            ['Tim Tahfidz Juara 1 MTQ Tingkat Provinsi', 'prestasi', ['Tahfidz']],
            ['Studi Kampus ke ITB Angkatan XI', 'kegiatan-sekolah', ['Riset']],
            ['Kajian Rutin Ba’da Dzuhur Resmi Diluncurkan', 'keislaman', ['Tahfidz']],
            ['Workshop Karya Tulis Ilmiah untuk Kelas XI', 'akademik', ['Riset', 'Literasi']],
            ['Pramuka Raih Penghargaan Duta Penggalang', 'ekstrakurikuler', ['Pramuka', 'Kepemimpinan']],
            ['Juara 2 Debat Bahasa Inggris Tingkat Kota', 'prestasi', ['Bahasa']],
            ['Bakti Sosial Ramadan di Panti Asuhan Al-Hikmah', 'kegiatan-sekolah', ['Bakti Sosial']],
            ['Hafidz 30 Juz Kembali Bertambah, Total 42 Siswa', 'keislaman', ['Tahfidz']],
            ['Simulasi UTBK untuk Kelas XII Dimulai', 'akademik', ['Literasi']],
            ['Pekan Seni dan Budaya 2026 Tampilkan 30 Karya Siswa', 'ekstrakurikuler', ['Seni']],
            ['Tim Basket Juara 1 Turnamen Antarsekolah', 'prestasi', ['Olahraga']],
            ['Kunjungan Industri ke Startup Teknologi Jakarta', 'kegiatan-sekolah', ['Robotik']],
            ['Pelatihan Adab dan Kepemimpinan Siswa (PAKSI)', 'keislaman', ['Kepemimpinan']],
            ['Program Pertukaran Pelajar dengan Negeri Jiran', 'akademik', ['Bahasa']],
            ['Kaderisasi OSIS Periode 2026/2027', 'ekstrakurikuler', ['Kepemimpinan']],
            ['Alumni Diterima di Universitas Ternama Luar Negeri', 'prestasi', ['Riset']],
            ['Peringatan Hari Guru Nasional Berlangsung Hangat', 'kegiatan-sekolah', ['Kepemimpinan']],
            ['Penilaian Tengah Semester Berbasis Komputer', 'akademik', ['Literasi']],
            ['Wisuda Tahfidz Angkatan XII Digelar di Masjid Sekolah', 'keislaman', ['Tahfidz']],
        ];

        foreach ($articles as $index => [$title, $categorySlug, $tags]) {
            $slug = 'berita-'.str($title)->slug()->limit(60, '')->toString();

            $news = News::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'excerpt' => 'Ringkasan '.strtolower($title).'. Simak rangkaian kegiatan dan capaian terbaru dari SMA Islam Nusantara.',
                    'content' => $this->body($title),
                    'featured_image' => $this->photo($slug, 1200, 800),
                    'author_id' => $author?->id,
                    'published_at' => now()->subDays(($index + 1) * 6),
                    'status' => 'published',
                    'seo_title' => $title.' — SMA Islam Nusantara',
                    'seo_description' => 'Berita '.$title.' dari SMA Islam Nusantara.',
                    'views_count' => 45 + $index * 13,
                ],
            );

            $news->categories()->sync(array_filter([$categoryIds[$categorySlug] ?? null]));
            $news->tags()->sync(
                collect($tags)->map(fn (string $tag) => $tagIds[$tag] ?? null)->filter()->values()->all(),
            );
        }

        $drafts = [
            ['Draf: Rencana Pembangunan Gedung Serbaguna', 'kegiatan-sekolah'],
            ['Draf: Jadwal Ujian Akhir Semester Gasal', 'akademik'],
            ['Draf: Panduan Ekstrakurikuler Tahun Depan', 'ekstrakurikuler'],
        ];

        foreach ($drafts as $index => [$title, $categorySlug]) {
            $slug = 'draf-'.str($title)->slug()->limit(60, '')->toString();

            News::updateOrCreate(['slug' => $slug], [
                'title' => $title,
                'excerpt' => 'Draf berita yang belum dipublikasikan.',
                'content' => '<p>Konten sedang disusun oleh tim redaksi sekolah.</p>',
                'author_id' => $author?->id,
                'status' => 'draft',
                'published_at' => null,
                'views_count' => 0,
            ])->categories()->sync(array_filter([$categoryIds[$categorySlug] ?? null]));
        }
    }

    private function body(string $title): string
    {
        return '<p>SMA Islam Nusantara kembali mencatatkan kegiatan penting melalui '.$title.'. Kegiatan ini merupakan bagian dari komitmen sekolah dalam memadukan keunggulan akademik dan pembinaan karakter Islami.</p>'
            .'<h2>Rangkaian Kegiatan</h2>'
            .'<p>Kegiatan berlangsung dengan melibatkan peserta didik, tenaga pendidik, dan dukungan penuh orang tua.</p>'
            .'<ul><li>Pembukaan dan sambutan pimpinan sekolah.</li><li>Sesi utama bersama narasumber.</li><li>Praktik dan pendampingan peserta didik.</li><li>Refleksi serta penutupan.</li></ul>'
            .'<blockquote>“Kami ingin setiap peserta didik tumbuh menjadi pribadi yang berilmu, berakhlak, dan berprestasi.”</blockquote>'
            .'<p>Dokumentasi lengkap kegiatan dapat dilihat pada album galeri sekolah. Ikuti terus kanal resmi kami untuk informasi terbaru.</p>';
    }
}
