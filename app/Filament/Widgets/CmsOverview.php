<?php

namespace App\Filament\Widgets;

use App\Models\Announcement;
use App\Models\Department;
use App\Models\News;
use App\Models\Page;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CmsOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Halaman Published', (string) Page::where('status', 'published')->count()),
            Stat::make('Halaman Draft', (string) Page::where('status', 'draft')->count()),
            Stat::make('Berita', (string) News::count()),
            Stat::make('Pengumuman Aktif', (string) Announcement::active()->count()),
            Stat::make('Jurusan', (string) Department::count()),
        ];
    }
}
