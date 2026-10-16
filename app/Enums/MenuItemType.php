<?php

namespace App\Enums;

enum MenuItemType: string
{
    case Page = 'page';
    case Department = 'department';
    case News = 'news';
    case Announcement = 'announcement';
    case Gallery = 'gallery';
    case CustomUrl = 'custom_url';
    case Dropdown = 'dropdown';

    public function label(): string
    {
        return match ($this) {
            self::Page => 'Halaman CMS',
            self::Department => 'Jurusan',
            self::News => 'Berita',
            self::Announcement => 'Pengumuman',
            self::Gallery => 'Galeri',
            self::CustomUrl => 'URL Khusus',
            self::Dropdown => 'Dropdown',
        };
    }

    /** @return list<string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
