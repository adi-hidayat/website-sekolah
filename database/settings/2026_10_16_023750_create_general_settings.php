<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $school = null;

        try {
            if (Schema::hasTable('school_settings')) {
                $school = DB::table('school_settings')->first();
            }
        } catch (Throwable) {
            $school = null;
        }

        $this->migrator->add('general.site_name', $school->school_name ?? 'MAS AL-MAHFUZHIYAH KALIDADI');
        $this->migrator->add('general.site_tagline', 'Madrasah Aliyah Swasta · Kalirejo, Lampung Tengah');
        $this->migrator->add('general.logo', $school->logo ?? null);
        $this->migrator->add('general.favicon', null);
        $this->migrator->add('general.seo_title', $school->school_name ?? 'MAS AL-MAHFUZHIYAH KALIDADI');
        $this->migrator->add('general.seo_description', 'Website resmi MAS AL-MAHFUZHIYAH KALIDADI, Madrasah Aliyah Swasta di Kalirejo, Kabupaten Lampung Tengah, Lampung.');
        $this->migrator->add('general.seo_keywords', 'MAS Al-Mahfuzhiyah, madrasah aliyah, Kalirejo, Lampung Tengah, NPSN 10816270, PPDB');
        $this->migrator->add('general.og_image', $school->logo ?? null);

        $this->migrator->add('general.school_name', $school->school_name ?? 'MAS AL-MAHFUZHIYAH KALIDADI');
        $this->migrator->add('general.short_name', $school->short_name ?? 'MAS Al-Mahfuzhiyah');
        $this->migrator->add('general.npsn', $school->npsn ?? '10816270');
        $this->migrator->add('general.accreditation', $school->accreditation ?? null);
        $this->migrator->add('general.address', $school->address ?? null);
        $this->migrator->add('general.phone', $school->phone ?? null);
        $this->migrator->add('general.email', $school->email ?? null);
        $this->migrator->add('general.website', $school->website ?? null);
        $this->migrator->add('general.principal_name', $school->principal_name ?? null);
        $this->migrator->add('general.principal_photo', $school->principal_photo ?? null);
        $this->migrator->add('general.principal_welcome', $school->principal_welcome ?? null);
        $this->migrator->add('general.vision', $school->vision ?? null);
        $this->migrator->add('general.mission', $school->mission ?? null);
        $this->migrator->add('general.history', $school->history ?? null);
        $this->migrator->add('general.maps_embed_url', $school->maps_embed_url ?? null);

        $socialLinks = null;
        if (isset($school->social_links) && $school->social_links !== null) {
            $decoded = is_string($school->social_links) ? json_decode($school->social_links, true) : $school->social_links;
            $socialLinks = is_array($decoded) ? $decoded : null;
        }
        $this->migrator->add('general.social_links', $socialLinks);

        $statistics = null;
        if (isset($school->statistics) && $school->statistics !== null) {
            $decoded = is_string($school->statistics) ? json_decode($school->statistics, true) : $school->statistics;
            $statistics = is_array($decoded) ? array_values($decoded) : null;
        }
        $this->migrator->add('general.statistics', $statistics);
    }
};
