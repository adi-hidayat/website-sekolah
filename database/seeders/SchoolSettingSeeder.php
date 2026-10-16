<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use App\Settings\GeneralSettings;
use Illuminate\Database\Seeder;

class SchoolSettingSeeder extends Seeder
{
    /**
     * Identitas MAS AL-MAHFUZHIYAH KALIDADI (dikenal juga sebagai
     * MA Al-Mahfuzhiyah 207 Kalidadi, NPSN 10816270) di bawah naungan
     * Pondok Pesantren Al-Mukhlish, Kalirejo, Kabupaten Lampung
     * Tengah, Lampung (PRD §5.4, §6.1).
     *
     * @see https://annibuku.com/sekolah/216773-mas-al-mahfuzhiyah-kalidadi
     */
    public function run(): void
    {
        $settings = SchoolSetting::query()->first() ?? new SchoolSetting;

        $settings->fill([
            'school_name' => 'MAS AL-MAHFUZHIYAH KALIDADI',
            'short_name' => 'MAS Al-Mahfuzhiyah',
            'npsn' => '10816270',
            'accreditation' => 'A',
            'address' => 'Jl. Pon-Pes Al-Mukhlish No. 3, Dusun IV, Desa Kalidadi, Kecamatan Kalirejo, Kabupaten Lampung Tengah, Lampung',
            'phone' => null,
            'email' => null,
            'website' => null,
            'logo' => null,
            'principal_name' => null,
            'principal_welcome' => null,
            'principal_photo' => null,
            'vision' => 'Mewujudkan generasi yang islami, berilmu, dan berprestasi.',
            'mission' => "1. Menyelenggarakan pendidikan yang memadukan nilai keislaman dan penguasaan sains.\n2. Membina akhlak, kedisiplinan, dan kemandirian peserta didik.\n3. Mengembangkan prestasi akademik, khususnya pada kompetisi sains tingkat kabupaten hingga provinsi.\n4. Mengantarkan lulusan melanjutkan ke perguruan tinggi melalui jalur nasional seperti SNBP dan SPAN-PTKIN.\n5. Menumbuhkan kepedulian sosial dan kepemimpinan.",
            'history' => '<p>MAS Al-Mahfuzhiyah Kalidadi — dikenal juga sebagai MA Al-Mahfuzhiyah 207 Kalidadi — adalah Madrasah Aliyah Swasta setingkat SMA yang berada di bawah naungan Pondok Pesantren Al-Mukhlish, dengan NPSN 10816270 dan akreditasi A.</p><p>Madrasah ini aktif mengantarkan para siswanya lulus ke perguruan tinggi melalui jalur nasional seperti SNBP dan SPAN-PTKIN. Siswa-siswinya juga aktif berprestasi di tingkat kabupaten hingga provinsi, salah satunya dalam ajang Olimpiade MIPA/Sains (OMI) untuk bidang studi Ekonomi, Biologi, dan Kimia.</p>',
            'maps_embed_url' => 'https://www.google.com/maps?q=-5.2325,104.9252&output=embed',
            'social_links' => [
                'instagram' => 'https://instagram.com/maalmahfuzhiyah',
                'tiktok' => 'https://tiktok.com/@ma.almahfuzhiyah',
            ],
            'statistics' => null,
        ])->save();

        $this->syncGeneralSettings($settings);
    }

    protected function syncGeneralSettings(SchoolSetting $settings): void
    {
        try {
            $general = app(GeneralSettings::class);

            $general->fill([
                'site_name' => $settings->school_name,
                'site_tagline' => 'Madrasah Aliyah Swasta · Kalirejo, Lampung Tengah',
                'logo' => $settings->logo,
                'favicon' => null,
                'seo_title' => $settings->school_name,
                'seo_description' => 'Website resmi MAS AL-MAHFUZHIYAH KALIDADI, Madrasah Aliyah Swasta di Kalirejo, Kabupaten Lampung Tengah, Lampung.',
                'seo_keywords' => 'MAS Al-Mahfuzhiyah, madrasah aliyah, Kalirejo, Lampung Tengah, NPSN 10816270, PPDB',
                'og_image' => null,
                'school_name' => $settings->school_name,
                'short_name' => $settings->short_name,
                'npsn' => $settings->npsn,
                'accreditation' => $settings->accreditation,
                'address' => $settings->address,
                'phone' => $settings->phone,
                'email' => $settings->email,
                'website' => $settings->website,
                'principal_name' => $settings->principal_name,
                'principal_photo' => $settings->principal_photo,
                'principal_welcome' => $settings->principal_welcome,
                'vision' => $settings->vision,
                'mission' => $settings->mission,
                'history' => $settings->history,
                'maps_embed_url' => $settings->maps_embed_url,
                'social_links' => $settings->social_links,
                'statistics' => $settings->statistics,
            ]);
            $general->save();
        } catch (\Throwable) {
            // Pengaturan Spatie boleh belum termigrasi saat seeder berjalan.
        }
    }
}
