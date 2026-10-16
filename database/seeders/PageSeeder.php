<?php

namespace Database\Seeders;

use App\Models\Page;
use Database\Seeders\Concerns\ProvidesPlaceholderImages;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    use ProvidesPlaceholderImages;

    /**
     * Halaman CMS & page builder (PRD §5, §6.1, §6.6).
     */
    public function run(): void
    {
        foreach ($this->pages() as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page + ['published_at' => now()->subDays(30)],
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function pages(): array
    {
        return [
            $this->page('Beranda', 'beranda', 'landing', 'Selamat datang di SMA Islam Nusantara.', [
                $this->hero(
                    'Membentuk Generasi Berilmu, Berakhlak, dan Berprestasi.',
                    'Lingkungan pendidikan yang memadukan keunggulan akademik, pembinaan karakter Islami, dan pengembangan potensi setiap peserta didik.',
                ),
                ['id' => 'statistik', 'type' => 'statistics', 'data' => []],
                ['id' => 'sambutan', 'type' => 'principal', 'data' => []],
                ['id' => 'program', 'type' => 'programs', 'data' => ['eyebrow' => 'Program', 'title' => 'Program dan Jurusan', 'description' => 'Pilihan program yang dirancang untuk mengembangkan kompetensi peserta didik.', 'limit' => 6]],
                ['id' => 'nilai', 'type' => 'values', 'data' => ['eyebrow' => 'Karakter', 'title' => 'Nilai dan Karakter Islami', 'description' => 'Pembinaan nilai yang menjiwai seluruh kegiatan belajar.', 'items' => $this->values()]],
                ['id' => 'berita', 'type' => 'latest_news', 'data' => ['eyebrow' => 'Informasi', 'title' => 'Berita dan Kegiatan', 'description' => 'Kabar terbaru seputar pembelajaran dan aktivitas sekolah.', 'limit' => 4]],
                ['id' => 'prestasi', 'type' => 'achievements', 'data' => ['eyebrow' => 'Capaian', 'title' => 'Prestasi Terbaru', 'limit' => 6]],
                ['id' => 'fasilitas', 'type' => 'facilities', 'data' => ['eyebrow' => 'Sarana', 'title' => 'Fasilitas Sekolah', 'limit' => 6]],
                ['id' => 'galeri', 'type' => 'gallery', 'data' => ['eyebrow' => 'Dokumentasi', 'title' => 'Galeri Kegiatan', 'limit' => 9]],
            ], true),

            $this->page('Profil Sekolah', 'profil', 'default', 'Profil, sejarah, dan identitas SMA Islam Nusantara.', [
                ['id' => 'sambutan', 'type' => 'principal', 'data' => []],
                ['id' => 'sejarah', 'type' => 'rich_text', 'data' => ['title' => 'Sejarah Sekolah', 'html' => '<p>SMA Islam Nusantara didirikan pada tahun 1985 oleh Yayasan Pendidikan Islam Nusantara. Berawal dari tiga ruang kelas, sekolah kini menjadi salah satu lembaga pendidikan menengah Islam terdepan di Jakarta.</p><p>Perjalanan panjang tersebut ditandai dengan pembangunan sarana pembelajaran, penguatan kurikulum, dan pembinaan karakter peserta didik.</p>']],
                ['id' => 'nilai', 'type' => 'values', 'data' => ['title' => 'Nilai yang Kami Pegang', 'items' => $this->values()]],
                ['id' => 'statistik', 'type' => 'statistics', 'data' => []],
                ['id' => 'fasilitas', 'type' => 'facilities', 'data' => ['title' => 'Fasilitas', 'limit' => 4]],
            ]),

            $this->page('Visi dan Misi', 'visi-misi', 'default', 'Visi dan misi SMA Islam Nusantara.', [
                ['id' => 'visi-misi', 'type' => 'rich_text', 'data' => ['title' => 'Visi dan Misi', 'html' => $this->visionMission()]],
                ['id' => 'nilai', 'type' => 'values', 'data' => ['title' => 'Nilai Inti', 'items' => $this->values()]],
            ]),

            $this->page('Struktur Organisasi', 'struktur-organisasi', 'default', 'Struktur organisasi dan kepengurusan sekolah.', [
                ['id' => 'pengantar', 'type' => 'rich_text', 'data' => ['title' => 'Struktur Organisasi', 'html' => '<p>Pengelolaan sekolah dijalankan secara kolaboratif oleh kepala sekolah, wakil kepala bidang, koordinator, dan tenaga pendidik.</p>']],
                ['id' => 'struktur', 'type' => 'card_grid', 'data' => ['title' => 'Pengurus Inti', 'cards' => [
                    ['icon' => 'users', 'title' => 'Kepala Sekolah', 'description' => 'Memimpin penyelenggaraan pendidikan dan pengelolaan sekolah.'],
                    ['icon' => 'book', 'title' => 'Bidang Kurikulum', 'description' => 'Mengembangkan kurikulum, pembelajaran, dan penilaian.'],
                    ['icon' => 'academic-cap', 'title' => 'Bidang Kesiswaan', 'description' => 'Membina karakter, kedisiplinan, dan kegiatan peserta didik.'],
                    ['icon' => 'building', 'title' => 'Bidang Sarana', 'description' => 'Mengelola sarana prasarana dan fasilitas pembelajaran.'],
                    ['icon' => 'globe', 'title' => 'Bidang Humas', 'description' => 'Menjalin hubungan dengan orang tua dan masyarakat.'],
                    ['icon' => 'sparkles', 'title' => 'Bidang Keislaman', 'description' => 'Mengembangkan program pembinaan keislaman dan tahfidz.'],
                ]]],
                ['id' => 'guru', 'type' => 'teachers', 'data' => ['title' => 'Tenaga Pendidik', 'limit' => 8]],
            ]),

            $this->page('Akademik', 'akademik', 'default', 'Program akademik dan kurikulum pembelajaran.', [
                ['id' => 'pengantar', 'type' => 'rich_text', 'data' => ['title' => 'Program Akademik', 'html' => '<p>Pembelajaran dilaksanakan dengan pendekatan berbasis kompetensi, proyek, dan pendampingan individual sesuai potensi peserta didik.</p>']],
                ['id' => 'program', 'type' => 'programs', 'data' => ['title' => 'Program dan Jurusan', 'limit' => 6]],
                ['id' => 'nilai', 'type' => 'values', 'data' => ['title' => 'Pendekatan Pembelajaran', 'items' => $this->values()]],
                ['id' => 'statistik', 'type' => 'statistics', 'data' => []],
            ]),

            $this->page('Ekstrakurikuler', 'ekstrakurikuler', 'default', 'Kegiatan pengembangan minat dan bakat peserta didik.', [
                ['id' => 'pengantar', 'type' => 'rich_text', 'data' => ['title' => 'Ekstrakurikuler', 'html' => '<p>Sekolah menyediakan beragam kegiatan ekstrakurikuler untuk mengembangkan minat, bakat, dan kepemimpinan peserta didik.</p>']],
                ['id' => 'daftar', 'type' => 'card_grid', 'data' => ['title' => 'Pilihan Kegiatan', 'cards' => $this->extracurriculars()]],
                ['id' => 'galeri', 'type' => 'gallery', 'data' => ['title' => 'Dokumentasi Kegiatan', 'limit' => 9]],
            ]),

            $this->page('Fasilitas', 'fasilitas', 'default', 'Sarana dan prasarana pendukung pembelajaran.', [
                ['id' => 'fasilitas', 'type' => 'facilities', 'data' => ['title' => 'Fasilitas Sekolah', 'limit' => 9]],
                ['id' => 'galeri', 'type' => 'gallery', 'data' => ['title' => 'Galeri Fasilitas', 'limit' => 6]],
                ['id' => 'kontak', 'type' => 'contact', 'data' => []],
            ]),

            $this->page('Prestasi', 'prestasi', 'default', 'Capaian peserta didik dan sekolah.', [
                ['id' => 'prestasi', 'type' => 'achievements', 'data' => ['title' => 'Prestasi Sekolah', 'limit' => 12]],
                ['id' => 'statistik', 'type' => 'statistics', 'data' => []],
            ]),

            $this->page('Informasi SPMB', 'spmb', 'landing', 'Informasi penerimaan peserta didik baru.', [
                $this->hero(
                    'Penerimaan Peserta Didik Baru 2026/2027',
                    'Bergabunglah bersama SMA Islam Nusantara untuk mengembangkan potensi akademik dan karakter Islami putra-putri Anda.',
                    'Daftar Sekarang',
                    '/spmb',
                ),
                ['id' => 'alur', 'type' => 'rich_text', 'data' => ['title' => 'Alur Pendaftaran', 'html' => '<ol><li>Pendaftaran daring melalui kanal resmi sekolah.</li><li>Pengumpulan dokumen administrasi.</li><li>Seleksi akademik dan wawancara.</li><li>Pengumuman hasil seleksi.</li><li>Daftar ulang peserta didik baru.</li></ol><p>Informasi jadwal dan persyaratan lengkap dapat menghubungi bagian administrasi sekolah.</p>']],
                ['id' => 'syarat', 'type' => 'card_grid', 'data' => ['title' => 'Persyaratan', 'cards' => [
                    ['icon' => 'book', 'title' => 'Dokumen Akademik', 'description' => 'Ijazah/surat keterangan lulus dan rapor semester terakhir.'],
                    ['icon' => 'user', 'title' => 'Identitas', 'description' => 'Akta kelahiran, kartu keluarga, dan kartu identitas orang tua.'],
                    ['icon' => 'image', 'title' => 'Foto', 'description' => 'Pas foto berwarna ukuran 3x4 sebanyak 4 lembar.'],
                ]]],
                ['id' => 'faq', 'type' => 'faq', 'data' => ['title' => 'Pertanyaan Seputar SPMB', 'items' => $this->faq()]],
            ]),

            $this->page('Kontak', 'kontak', 'contact', 'Hubungi SMA Islam Nusantara.', [
                ['id' => 'kontak', 'type' => 'contact', 'data' => ['title' => 'Hubungi Kami']],
                ['id' => 'faq', 'type' => 'faq', 'data' => ['title' => 'Pertanyaan Umum', 'items' => $this->faq()]],
                ['id' => 'peta', 'type' => 'image', 'data' => ['src' => $this->photo('peta-sekolah', 1600, 900), 'caption' => 'Denah dan lokasi sekolah.']],
            ]),

            $this->page('FAQ', 'faq', 'default', 'Pertanyaan yang sering diajukan.', [
                ['id' => 'faq', 'type' => 'faq', 'data' => ['title' => 'Pertanyaan yang Sering Diajukan', 'items' => $this->faq()]],
            ]),

            $this->page('Kalender Akademik', 'kalender-akademik', 'default', 'Jadwal kegiatan akademik sekolah.', [
                ['id' => 'kalender', 'type' => 'rich_text', 'data' => ['title' => 'Kalender Akademik 2026/2027', 'html' => '<ul><li>Juli: Awal tahun ajaran dan masa pengenalan lingkungan sekolah.</li><li>September: Penilaian tengah semester.</li><li>Desember: Penilaian akhir semester dan pembagian rapor.</li><li>Maret: Ujian sekolah dan asesmen nasional.</li><li>Juni: Kelulusan dan wisuda tahfidz.</li></ul>']],
            ]),

            $this->page('Kebijakan Privasi', 'kebijakan-privasi', 'default', 'Kebijakan privasi situs sekolah.', [
                ['id' => 'privasi', 'type' => 'rich_text', 'data' => ['title' => 'Kebijakan Privasi', 'html' => '<p>Situs ini dikelola oleh SMA Islam Nusantara. Kami menghormati privasi pengunjung dan hanya mengumpulkan data yang diperlukan untuk meningkatkan layanan informasi sekolah.</p><p>Data yang dikirim melalui formulir hanya digunakan untuk keperluan komunikasi resmi dan tidak dibagikan kepada pihak ketiga tanpa izin.</p>']],
            ]),

            $this->page('Draf: Laporan Tahunan Sekolah', 'draf-laporan-tahunan', 'default', 'Draf laporan tahunan.', [
                ['id' => 'draf', 'type' => 'rich_text', 'data' => ['html' => '<p>Konten laporan tahunan sedang disusun.</p>']],
            ], false, 'draft', false),

            $this->page('Draf: Penerimaan Tenaga Pendidik', 'draf-penerimaan-tenaga-pendidik', 'default', 'Draf informasi penerimaan tenaga pendidik.', [
                ['id' => 'draf', 'type' => 'rich_text', 'data' => ['html' => '<p>Informasi lowongan tenaga pendidik sedang disusun.</p>']],
            ], false, 'draft', false),
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $sections
     * @return array<string, mixed>
     */
    private function page(
        string $title,
        string $slug,
        string $template,
        string $excerpt,
        array $sections,
        bool $withImage = true,
        string $status = 'published',
        bool $publishedAt = true,
    ): array {
        return [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'template' => $template,
            'featured_image' => $withImage ? $this->photo('halaman-'.$slug, 1600, 900) : null,
            'status' => $status,
            'published_at' => $publishedAt ? now()->subDays(30) : null,
            'seo_title' => $title.' — SMA Islam Nusantara',
            'seo_description' => $excerpt,
            'sections' => ['version' => 1, 'sections' => $sections],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function hero(string $title, string $description, string $ctaLabel = 'Daftar Sekarang', string $ctaUrl = '/spmb'): array
    {
        return [
            'id' => 'hero',
            'type' => 'hero',
            'data' => [
                'eyebrow' => 'Sekolah Menengah Atas Islam',
                'title' => $title,
                'description' => $description,
                'image' => $this->photo('hero-'.str($title)->slug()->limit(40, '')->toString(), 1200, 1500),
                'cta' => ['label' => $ctaLabel, 'url' => $ctaUrl],
                'secondary' => ['label' => 'Jelajahi Sekolah', 'url' => '#program'],
            ],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function values(): array
    {
        return [
            ['icon' => 'heart', 'title' => 'Adab dan Akhlak', 'description' => 'Membiasakan akhlak mulia dalam pergaulan dan kehidupan sehari-hari.'],
            ['icon' => 'academic-cap', 'title' => 'Keunggulan Akademik', 'description' => 'Mendorong prestasi belajar melalui pembelajaran yang terarah.'],
            ['icon' => 'shield-check', 'title' => 'Kedisiplinan dan Tanggung Jawab', 'description' => 'Menumbuhkan kemandirian dan tanggung jawab peserta didik.'],
            ['icon' => 'users', 'title' => 'Kepedulian Sosial', 'description' => 'Melatih kepekaan dan kepedulian terhadap sesama.'],
            ['icon' => 'sparkles', 'title' => 'Pengembangan Potensi Diri', 'description' => 'Memfasilitasi minat dan bakat melalui berbagai kegiatan.'],
            ['icon' => 'book', 'title' => 'Budaya Literasi', 'description' => 'Menumbuhkan kebiasaan membaca, menulis, dan berpikir kritis.'],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function extracurriculars(): array
    {
        return [
            ['icon' => 'users', 'title' => 'Pramuka', 'description' => 'Pembinaan kemandirian dan kepemimpinan.'],
            ['icon' => 'sparkles', 'title' => 'Robotik', 'description' => 'Perancangan dan pemrograman robot.'],
            ['icon' => 'book', 'title' => 'Karya Ilmiah Remaja', 'description' => 'Penelitian dan penulisan ilmiah.'],
            ['icon' => 'trophy', 'title' => 'Olahraga', 'description' => 'Basket, futsal, voli, dan bulu tangkis.'],
            ['icon' => 'heart', 'title' => 'Seni dan Musik', 'description' => 'Paduan suara, teater, dan seni tradisional.'],
            ['icon' => 'globe', 'title' => 'English Club', 'description' => 'Debat, public speaking, dan storytelling.'],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function faq(): array
    {
        return [
            ['q' => 'Kapan pendaftaran peserta didik baru dibuka?', 'a' => '<p>Pendaftaran dibuka setiap awal tahun ajaran. Jadwal resmi diumumkan melalui halaman pengumuman dan kanal media sosial sekolah.</p>'],
            ['q' => 'Apa saja program jurusan yang tersedia?', 'a' => '<p>Tersedia peminatan MIPA, IPS, Bahasa dan Budaya, Ilmu Keagamaan dan Tahfidz, Informatika, serta program Riset dan Olimpiade.</p>'],
            ['q' => 'Apakah tersedia beasiswa?', 'a' => '<p>Ya, sekolah menyediakan beasiswa prestasi akademik dan beasiswa bagi keluarga kurang mampu sesuai ketentuan yayasan.</p>'],
            ['q' => 'Bagaimana sistem pembinaan keislaman?', 'a' => '<p>Pembinaan dilakukan melalui kegiatan harian, kajian rutin, tahfidz, dan program pembinaan akhlak terpadu.</p>'],
        ];
    }

    private function visionMission(): string
    {
        return '<h3>Visi</h3><p>Menjadi sekolah Islam unggul yang melahirkan generasi berilmu, berakhlak mulia, berprestasi, dan berwawasan global.</p>'
            .'<h3>Misi</h3><ol><li>Menyelenggarakan pembelajaran berkualitas berbasis nilai keislaman.</li><li>Membina adab, akhlak, dan kedisiplinan peserta didik.</li><li>Mengembangkan potensi akademik dan non-akademik secara berimbang.</li><li>Membangun budaya literasi, riset, dan teknologi.</li><li>Menumbuhkan kepedulian sosial dan kepemimpinan.</li></ol>';
    }
}
