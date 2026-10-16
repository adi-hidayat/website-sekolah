<?php

namespace App\Observers;

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

        if ($item->type->value !== 'dropdown' && $item->resolvedUrl() === null && $item->is_visible) {
            // Biarkan tersimpan, tetapi resolver akan menyembunyikannya di publik.
        }
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
}
