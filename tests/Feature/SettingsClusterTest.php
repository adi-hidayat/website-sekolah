<?php

use App\Filament\Clusters\Settings\Pages\ManageCustomization;
use App\Filament\Clusters\Settings\Pages\ManageGeneral;
use App\Filament\Clusters\Settings\SettingsCluster;
use App\Models\User;
use App\Settings\CustomizationSettings;
use App\Settings\GeneralSettings;
use App\Support\SitePresentation;
use Inertia\Testing\AssertableInertia;

it('persists general settings with website and organization tabs', function () {
    $settings = app(GeneralSettings::class);

    expect($settings->site_name)->not->toBeEmpty()
        ->and($settings->school_name)->not->toBeEmpty();

    $settings->fill([
        'site_name' => 'Sekolah Uji',
        'seo_description' => 'Deskripsi uji untuk SEO.',
        'school_name' => 'SMA Uji Coba',
        'npsn' => '99999999',
        'phone' => '0721123456',
        'whatsapp' => '6281234567890',
    ]);
    $settings->save();

    $fresh = app(GeneralSettings::class);

    expect($fresh->site_name)->toBe('Sekolah Uji')
        ->and($fresh->seo_description)->toBe('Deskripsi uji untuk SEO.')
        ->and($fresh->school_name)->toBe('SMA Uji Coba')
        ->and($fresh->npsn)->toBe('99999999')
        ->and($fresh->phone)->toBe('0721123456')
        ->and($fresh->whatsapp)->toBe('6281234567890');
});

it('persists customization settings for accent color and fonts', function () {
    $settings = app(CustomizationSettings::class);

    $settings->fill([
        'primary_color' => '#1d4ed8',
        'accent_color' => '#f59e0b',
        'font_body' => 'Poppins',
        'font_heading' => 'Montserrat',
    ]);
    $settings->save();

    $fresh = app(CustomizationSettings::class);

    expect($fresh->primary_color)->toBe('#1d4ed8')
        ->and($fresh->accent_color)->toBe('#f59e0b')
        ->and($fresh->font_body)->toBe('Poppins')
        ->and($fresh->font_heading)->toBe('Montserrat');
});

it('exposes theme css variables derived from the accent color', function () {
    app(CustomizationSettings::class)->fill([
        'primary_color' => '#1d4ed8',
        'accent_color' => '#f59e0b',
        'font_body' => 'Poppins',
        'font_heading' => 'Montserrat',
    ])->save();

    SitePresentation::clearCaches();

    $variables = SitePresentation::cssVariables();

    expect($variables['--color-primary'])->toBe('#1d4ed8')
        ->and($variables['--color-accent'])->toBe('#f59e0b')
        ->and($variables['--color-primary-dark'])->not->toBe('#1d4ed8')
        ->and($variables['--font-sans'])->toContain('Poppins')
        ->and($variables['--font-display'])->toContain('Montserrat');
});

it('registers the settings cluster with general and customization pages', function () {
    expect(ManageGeneral::getCluster())->toBe(SettingsCluster::class)
        ->and(ManageCustomization::getCluster())->toBe(SettingsCluster::class)
        ->and(SettingsCluster::getClusteredComponents())->toContain(ManageGeneral::class)
        ->and(SettingsCluster::getClusteredComponents())->toContain(ManageCustomization::class)
        ->and(SettingsCluster::getNavigationGroup())->toBe('Pengaturan');
});

it('renders the settings pages for admins', function () {
    $this->actingAs(User::factory()->create());

    $this->get(ManageGeneral::getUrl())->assertOk();
    $this->get(ManageCustomization::getUrl())->assertOk();
});

it('shares site settings and theme on the homepage', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('siteSettings')
            ->has('siteTheme')
            ->where('siteTheme.primary_color', fn (?string $value) => is_string($value) && $value !== ''));
});

it('returns theme data from the public site settings api', function () {
    $this->getJson('/api/v1/site-settings')
        ->assertOk()
        ->assertJsonStructure(['data' => ['school_name', 'theme']]);
});
