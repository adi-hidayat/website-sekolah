<?php

namespace App\Enums;

enum PageTemplate: string
{
    case Default = 'default';
    case Landing = 'landing';
    case FullWidth = 'full-width';
    case Article = 'article';
    case Contact = 'contact';
    case Department = 'jurusan';

    public function label(): string
    {
        return match ($this) {
            self::Default => 'Default',
            self::Landing => 'Landing',
            self::FullWidth => 'Full Width',
            self::Article => 'Artikel',
            self::Contact => 'Kontak',
            self::Department => 'Jurusan',
        };
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
