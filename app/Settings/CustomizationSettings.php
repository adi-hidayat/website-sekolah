<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class CustomizationSettings extends Settings
{
    public string $primary_color;

    public string $accent_color;

    public string $font_body;

    public string $font_heading;

    public static function group(): string
    {
        return 'customization';
    }

    /**
     * @return array{font_body: string, font_heading: string}
     */
    public static function fontOptions(): array
    {
        return [
            'Inter' => 'Inter',
            'Plus Jakarta Sans' => 'Plus Jakarta Sans',
            'Poppins' => 'Poppins',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Nunito' => 'Nunito',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function googleFontFamilies(): array
    {
        return [
            'Inter' => 'Inter:wght@400;500;600;700',
            'Plus Jakarta Sans' => 'Plus+Jakarta+Sans:wght@400;500;600;700;800',
            'Poppins' => 'Poppins:wght@400;500;600;700',
            'Roboto' => 'Roboto:wght@400;500;700',
            'Open Sans' => 'Open+Sans:wght@400;600;700',
            'Lato' => 'Lato:wght@400;700;900',
            'Montserrat' => 'Montserrat:wght@400;500;600;700',
            'Nunito' => 'Nunito:wght@400;600;700;800',
        ];
    }
}
