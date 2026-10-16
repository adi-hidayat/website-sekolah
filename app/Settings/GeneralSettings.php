<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $site_name;

    public ?string $site_tagline = null;

    public ?string $logo = null;

    public ?string $favicon = null;

    public ?string $seo_title = null;

    public ?string $seo_description = null;

    public ?string $seo_keywords = null;

    public ?string $og_image = null;

    public string $school_name;

    public ?string $short_name = null;

    public ?string $npsn = null;

    public ?string $accreditation = null;

    public ?string $address = null;

    public ?string $phone = null;

    public ?string $whatsapp = null;

    public ?string $email = null;

    public ?string $website = null;

    public ?string $principal_name = null;

    public ?string $principal_photo = null;

    public ?string $principal_welcome = null;

    public ?string $vision = null;

    public ?string $mission = null;

    public ?string $history = null;

    public ?string $maps_embed_url = null;

    public ?array $social_links = null;

    public ?array $statistics = null;

    public static function group(): string
    {
        return 'general';
    }
}
