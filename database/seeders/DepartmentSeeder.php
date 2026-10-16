<?php

namespace Database\Seeders;

use App\Models\Department;
use Database\Seeders\Concerns\ProvidesPlaceholderImages;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    use ProvidesPlaceholderImages;

    /**
     * Program dan jurusan (PRD §5.6, §6.2).
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Matematika dan Ilmu Pengetahuan Alam',
                'code' => 'MIPA',
                'slug' => 'mipa',
                'short_description' => 'Peminatan sains dan teknologi dengan penguatan praktikum serta riset peserta didik.',
                'competencies' => ['Matematika Lanjut', 'Fisika', 'Kimia', 'Biologi', 'Informatika', 'Metodologi Riset'],
            ],
            [
                'name' => 'Ilmu Pengetahuan Sosial',
                'code' => 'IPS',
                'slug' => 'ips',
                'short_description' => 'Peminatan sosial, ekonomi, dan humaniora dengan kajian kontekstual.',
                'competencies' => ['Ekonomi', 'Sosiologi', 'Geografi', 'Sejarah', 'Antropologi', 'Kewirausahaan'],
            ],
            [
                'name' => 'Bahasa dan Budaya',
                'code' => 'BHS',
                'slug' => 'bahasa-dan-budaya',
                'short_description' => 'Penguatan kemampuan bahasa asing, sastra, dan literasi budaya.',
                'competencies' => ['Bahasa Inggris Lanjut', 'Bahasa Arab', 'Sastra Indonesia', 'Literasi Digital', 'Public Speaking'],
            ],
            [
                'name' => 'Ilmu Keagamaan dan Tahfidz',
                'code' => 'KEA',
                'slug' => 'ilmu-keagamaan',
                'short_description' => 'Pembinaan hafalan Al-Qur’an, kajian keislaman, dan pembentukan akhlak mulia.',
                'competencies' => ['Tahfidz Al-Qur’an', 'Tafsir Dasar', 'Fiqih', 'Sejarah Kebudayaan Islam', 'Bahasa Arab'],
            ],
            [
                'name' => 'Informatika dan Teknologi',
                'code' => 'INF',
                'slug' => 'informatika',
                'short_description' => 'Literasi teknologi, pemrograman dasar, dan pengembangan produk digital.',
                'competencies' => ['Pemrograman Dasar', 'Basis Data', 'Jaringan Komputer', 'Desain UI/UX', 'Keamanan Informasi'],
            ],
            [
                'name' => 'Riset dan Olimpiade Sains',
                'code' => 'ROS',
                'slug' => 'riset-dan-olimpiade',
                'short_description' => 'Program pembinaan intensif untuk kompetisi sains dan karya tulis ilmiah.',
                'competencies' => ['Karya Tulis Ilmiah', 'Olimpiade Sains', 'Analisis Data', 'Presentasi Ilmiah'],
            ],
        ];

        foreach ($departments as $index => $department) {
            Department::updateOrCreate(
                ['slug' => $department['slug']],
                [
                    'name' => $department['name'],
                    'code' => $department['code'],
                    'short_description' => $department['short_description'],
                    'description' => '<p>'.$department['name'].' dirancang untuk membekali peserta didik dengan kompetensi yang relevan dan berlandaskan nilai keislaman.</p><p>Pembelajaran dilaksanakan melalui kombinasi teori, praktik, proyek kolaboratif, dan pembinaan individual.</p>',
                    'thumbnail' => $this->photo('jurusan-'.$department['slug'], 1200, 800),
                    'banner' => $this->photo('banner-'.$department['slug'], 1600, 900),
                    'competencies' => $department['competencies'],
                    'seo_title' => $department['name'].' — SMA Islam Nusantara',
                    'seo_description' => $department['short_description'],
                    'status' => 'published',
                    'sort_order' => $index + 1,
                ],
            );
        }
    }
}
