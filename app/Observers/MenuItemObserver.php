<?php

namespace App\Observers;

use App\Enums\MenuItemType;
use App\Models\ActivityLog;
use App\Models\MenuItem;
use App\Support\MenuResolver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class MenuItemObserver
{
    public function saving(MenuItem $item): void
    {
        if (MenuResolver::wouldCreateCycle($item->id, $item->parent_id)) {
            throw ValidationException::withMessages([
                'parent_id' => 'Parent menu tidak valid (siklus terdeteksi).',
            ]);
        }

        $this->normalizeTargets($item);
    }

    public function saved(MenuItem $item): void
    {
        ActivityLog::record('menu.saved', $item, "Menu '{$item->label}' disimpan.");
        Cache::flush();
    }

    public function deleted(MenuItem $item): void
    {
        ActivityLog::record('menu.deleted', $item, "Menu '{$item->label}' dihapus.");
        Cache::flush();
    }

    /**
     * Hanya kolom target yang relevan dengan jenis item yang disimpan, agar
     * tidak ada referensi konten lama yang tertinggal (PRD 6.5 & 9.3).
     */
    protected function normalizeTargets(MenuItem $item): void
    {
        $item->page_id = $item->type === MenuItemType::Page ? $item->page_id : null;
        $item->department_id = $item->type === MenuItemType::Department ? $item->department_id : null;
        $item->news_id = $item->type === MenuItemType::News ? $item->news_id : null;
        $item->announcement_id = $item->type === MenuItemType::Announcement ? $item->announcement_id : null;
        $item->gallery_id = $item->type === MenuItemType::Gallery ? $item->gallery_id : null;

        if ($item->type !== MenuItemType::CustomUrl) {
            $item->target_url = null;
        }
    }
}
