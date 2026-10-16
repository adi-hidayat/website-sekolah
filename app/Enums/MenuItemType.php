<?php

namespace App\Enums;

enum MenuItemType: string
{
    case Page = 'page';
    case Department = 'department';
    case News = 'news';
    case Announcement = 'announcement';
    case CustomUrl = 'custom_url';
    case Dropdown = 'dropdown';

    public function label(): string
    {
        return match ($this) {
            self::Page => 'Halaman CMS',
            self::Department => 'Jurusan',
            self::News => 'Berita',
            self::Announcement => 'Pengumuman',
            self::CustomUrl => 'URL Khusus',
            self::Dropdown => 'Dropdown',
        };
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
