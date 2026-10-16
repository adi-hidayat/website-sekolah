<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Teacher;
use Database\Seeders\Concerns\ProvidesPlaceholderImages;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    use ProvidesPlaceholderImages;

    /**
     * Tenaga pendidik dan kependidikan (PRD §6.2).
     */
    public function run(): void
    {
        $teachers = [
            ['name' => 'Drs. H. Ahmad Suryana, M.Pd.', 'position' => 'Kepala Sekolah', 'subject' => 'Manajemen Pendidikan', 'department' => null, 'nip' => '196504121990031001'],
            ['name' => 'Hj. Siti Maryam, M.Pd.', 'position' => 'Wakil Kepala Bidang Kurikulum', 'subject' => 'Matematika', 'department' => 'mipa', 'nip' => '197203152000032002'],
            ['name' => 'Muhammad Rizki, S.Pd.', 'position' => 'Wakil Kepala Bidang Kesiswaan', 'subject' => 'Pendidikan Jasmani', 'department' => null, 'nip' => '198001202005011003'],
            ['name' => 'Nurul Hidayah, S.Si.', 'position' => 'Guru', 'subject' => 'Fisika', 'department' => 'mipa', 'nip' => '198706142010012004'],
            ['name' => 'Budi Santoso, S.Pd.', 'position' => 'Guru', 'subject' => 'Kimia', 'department' => 'mipa', 'nip' => '198403022009011005'],
            ['name' => 'Raden Ayu Kartika, S.Pd.', 'position' => 'Wali Kelas XI', 'subject' => 'Biologi', 'department' => 'mipa', 'nip' => '199002172014032006'],
            ['name' => 'Irfan Maulana, S.Kom.', 'position' => 'Guru', 'subject' => 'Informatika', 'department' => 'informatika', 'nip' => '199105232015011007'],
            ['name' => 'Dewi Puspitasari, S.Pd.', 'position' => 'Guru', 'subject' => 'Ekonomi', 'department' => 'ips', 'nip' => '198809112012012008'],
            ['name' => 'Agus Setiawan, S.Pd.', 'position' => 'Wali Kelas X', 'subject' => 'Sejarah', 'department' => 'ips', 'nip' => '198605082011011009'],
            ['name' => 'Rina Marlina, S.Pd.', 'position' => 'Guru', 'subject' => 'Sosiologi', 'department' => 'ips', 'nip' => '199203252016042010'],
            ['name' => 'Hendra Gunawan, S.Pd.', 'position' => 'Guru', 'subject' => 'Geografi', 'department' => 'ips', 'nip' => '198711192011011011'],
            ['name' => 'Ust. Abdul Hakim, Lc.', 'position' => 'Guru', 'subject' => 'Pendidikan Agama Islam', 'department' => 'ilmu-keagamaan', 'nip' => '198302142008011012'],
            ['name' => 'Usth. Aisyah Rahmawati, S.Ag.', 'position' => 'Pembina Tahfidz', 'subject' => 'Tahfidz Al-Qur’an', 'department' => 'ilmu-keagamaan', 'nip' => '199006302014032013'],
            ['name' => 'Muhammad Fauzan, S.Pd.I.', 'position' => 'Guru', 'subject' => 'Bahasa Arab', 'department' => 'ilmu-keagamaan', 'nip' => '198909122013011014'],
            ['name' => 'Sarah Amelia, S.S.', 'position' => 'Guru', 'subject' => 'Bahasa Inggris', 'department' => 'bahasa-dan-budaya', 'nip' => '199107142015032015'],
            ['name' => 'Yusuf Ramadhan, S.Pd.', 'position' => 'Guru', 'subject' => 'Bahasa Indonesia', 'department' => 'bahasa-dan-budaya', 'nip' => '198802222012011016'],
            ['name' => 'Fitriani, S.Psi.', 'position' => 'Guru Bimbingan Konseling', 'subject' => 'Bimbingan Konseling', 'department' => null, 'nip' => '199304082017042017'],
            ['name' => 'Tri Wibowo, S.Pd.', 'position' => 'Pembina Olimpiade', 'subject' => 'Matematika', 'department' => 'riset-dan-olimpiade', 'nip' => '198411052009011018'],
            ['name' => 'Lestari Ningsih, S.Pd.', 'position' => 'Wali Kelas XII', 'subject' => 'Matematika', 'department' => 'mipa', 'nip' => '198507192010012019'],
            ['name' => 'Fajar Nugroho, S.Kom.', 'position' => 'Pembina Ekstrakurikuler', 'subject' => 'Rekayasa Perangkat Lunak', 'department' => 'informatika', 'nip' => '199209302016011020'],
        ];

        foreach ($teachers as $index => $teacher) {
            $department = $teacher['department'] !== null
                ? Department::where('slug', $teacher['department'])->first()
                : null;

            Teacher::updateOrCreate(
                ['name' => $teacher['name']],
                [
                    'nip' => $teacher['nip'],
                    'position' => $teacher['position'],
                    'subject' => $teacher['subject'],
                    'photo' => $this->photo('guru-'.$teacher['nip'], 600, 800),
                    'bio' => 'Mendampingi peserta didik mengembangkan potensi akademik dan karakter Islami.',
                    'department_id' => $department?->id,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }
}
