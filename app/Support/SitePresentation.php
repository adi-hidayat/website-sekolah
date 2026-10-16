<?php

namespace App\Support;

use App\Models\SchoolSetting;
use App\Settings\CustomizationSettings;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\Cache;

/**
 * Presentasi situs publik dari Spatie Settings.
 *
 * Sumber utama: GeneralSettings (identitas + SEO) dan
 * CustomizationSettings (warna aksen + font). Model SchoolSetting
 * dipakai sebagai fallback bila settings belum termigrasi.
 */
class SitePresentation
{
    /**
     * @return array<string, mixed>
     */
    public static function general(): array
    {
        try {
            return Cache::remember('site-settings-array', 3600, function (): array {
                $settings = app(GeneralSettings::class);

                return $settings->toArray();
            });
        } catch (\Throwable) {
            try {
                return SchoolSetting::current()->toArray();
            } catch (\Throwable) {
                return [];
            }
        }
    }

    /**
     * @return array{primary_color: string, accent_color: string, font_body: string, font_heading: string}
     */
    public static function theme(): array
    {
        try {
            return Cache::remember('site-theme', 3600, function (): array {
                $settings = app(CustomizationSettings::class);

                return [
                    'primary_color' => $settings->primary_color,
                    'accent_color' => $settings->accent_color,
                    'font_body' => $settings->font_body,
                    'font_heading' => $settings->font_heading,
                ];
            });
        } catch (\Throwable) {
            return [
                'primary_color' => '#176b45',
                'accent_color' => '#b9d9a8',
                'font_body' => 'Inter',
                'font_heading' => 'Plus Jakarta Sans',
            ];
        }
    }

    public static function clearCaches(): void
    {
        Cache::forget('site-settings-array');
        Cache::forget('site-theme');
        Cache::forget('site-page-collections');
    }

    /**
     * @return array<string, string>
     */
    public static function cssVariables(): array
    {
        $theme = self::theme();

        $primary = self::normalizeHex($theme['primary_color'] ?? '#176b45');
        $accent = self::normalizeHex($theme['accent_color'] ?? '#b9d9a8');

        return [
            '--color-primary' => $primary,
            '--color-primary-dark' => self::mix($primary, '#000000', 0.22),
            '--color-primary-light' => self::mix($primary, '#ffffff', 0.87),
            '--color-accent' => $accent,
            '--font-sans' => "'{$theme['font_body']}', ui-sans-serif, system-ui, sans-serif",
            '--font-display' => "'{$theme['font_heading']}', 'Inter', ui-sans-serif, system-ui, sans-serif",
        ];
    }

    public static function inlineStyle(): string
    {
        $parts = [];

        foreach (self::cssVariables() as $name => $value) {
            $parts[] = "{$name}: {$value};";
        }

        return implode(' ', $parts);
    }

    public static function fontUrl(): ?string
    {
        $theme = self::theme();
        $families = CustomizationSettings::googleFontFamilies();

        $picked = array_values(array_filter([
            $families[$theme['font_body']] ?? null,
            $families[$theme['font_heading']] ?? null,
        ]));

        $picked = array_values(array_unique($picked));

        if ($picked === []) {
            return null;
        }

        return 'https://fonts.googleapis.com/css2?'.http_build_query([
            'family' => $picked,
            'display' => 'swap',
        ], '', '&', PHP_QUERY_RFC3986);
    }

    public static function logoUrl(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'data:')) {
            return $path;
        }

        if (str_starts_with($path, '/')) {
            return $path;
        }

        return '/storage/'.ltrim(preg_replace('#^storage/#', '', $path) ?? $path, '/');
    }

    private static function normalizeHex(string $hex): string
    {
        $hex = strtolower(trim($hex));

        if (preg_match('/^#[0-9a-f]{3}$/', $hex) === 1) {
            return '#'.$hex[1].$hex[1].$hex[2].$hex[2].$hex[3].$hex[3];
        }

        if (preg_match('/^#[0-9a-f]{6}$/', $hex) === 1) {
            return $hex;
        }

        return '#176b45';
    }

    private static function mix(string $hex1, string $hex2, float $weightOfSecond): string
    {
        $rgb1 = self::toRgb($hex1);
        $rgb2 = self::toRgb($hex2);
        $w = min(1, max(0, $weightOfSecond));

        $mix = [
            (int) round($rgb1[0] * (1 - $w) + $rgb2[0] * $w),
            (int) round($rgb1[1] * (1 - $w) + $rgb2[1] * $w),
            (int) round($rgb1[2] * (1 - $w) + $rgb2[2] * $w),
        ];

        return sprintf('#%02x%02x%02x', $mix[0], $mix[1], $mix[2]);
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private static function toRgb(string $hex): array
    {
        $hex = ltrim(self::normalizeHex($hex), '#');

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
