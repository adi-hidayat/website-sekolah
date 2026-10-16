import type { PageSection } from '@/types/cms';

/**
 * Komposisi section default homepage saat admin belum menyusun page builder
 * (PRD 5 & 8.3). Setiap section tetap mengambil data dari CMS/DB, dan
 * section tanpa data akan menyembunyikan dirinya sendiri.
 */
export function defaultHomepageSections(): PageSection[] {
    return [
        {
            id: 'hero',
            type: 'hero',
            data: {
                eyebrow: 'Sekolah Menengah Atas Islam',
                title: 'Membentuk Generasi Berilmu, Berakhlak, dan Berprestasi.',
                description:
                    'Lingkungan pendidikan yang memadukan keunggulan akademik, pembinaan karakter Islami, dan pengembangan potensi setiap peserta didik.',
                cta: { label: 'Daftar Sekarang', url: '' },
                secondary: { label: 'Jelajahi Sekolah', url: '#program' },
            },
        },
        { id: 'statistics', type: 'statistics', data: {} },
        {
            id: 'program',
            type: 'programs',
            data: {
                eyebrow: 'Program',
                title: 'Program dan Jurusan',
                description:
                    'Pilihan program yang dirancang untuk mengembangkan kompetensi akademik dan keterampilan peserta didik.',
            },
        },
        {
            id: 'nilai',
            type: 'values',
            data: {
                eyebrow: 'Karakter',
                title: 'Nilai dan Karakter Islami',
                description:
                    'Pembinaan nilai yang menjiwai seluruh kegiatan belajar di sekolah.',
                items: [
                    {
                        icon: 'heart',
                        title: 'Adab dan Akhlak',
                        description:
                            'Membiasakan akhlak mulia dalam pergaulan dan kehidupan sehari-hari.',
                    },
                    {
                        icon: 'academic-cap',
                        title: 'Keunggulan Akademik',
                        description:
                            'Mendorong prestasi belajar melalui pembelajaran yang terarah.',
                    },
                    {
                        icon: 'shield-check',
                        title: 'Kedisiplinan dan Tanggung Jawab',
                        description:
                            'Menumbuhkan kemandirian dan tanggung jawab peserta didik.',
                    },
                    {
                        icon: 'users',
                        title: 'Kepedulian Sosial',
                        description:
                            'Melatih kepekaan dan kepedulian terhadap sesama.',
                    },
                    {
                        icon: 'sparkles',
                        title: 'Pengembangan Potensi Diri',
                        description:
                            'Memfasilitasi minat dan bakat melalui berbagai kegiatan.',
                    },
                ],
            },
        },
        { id: 'sambutan', type: 'principal', data: {} },
        {
            id: 'berita',
            type: 'latest_news',
            data: {
                eyebrow: 'Informasi',
                title: 'Berita dan Kegiatan',
                description:
                    'Kabar terbaru seputar kegiatan pembelajaran dan aktivitas sekolah.',
            },
        },
        { id: 'prestasi', type: 'achievements', data: {} },
        { id: 'fasilitas', type: 'facilities', data: {} },
        { id: 'galeri', type: 'gallery', data: {} },
    ];
}
