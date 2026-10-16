<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Page;
use App\Models\SlugRedirect;
use App\Support\PageSections;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    public function saving(Page $page): void
    {
        if ($page->sections !== null) {
            $page->sections = PageSections::normalize($page->sections);
        }

        if (auth()->check()) {
            $userId = auth()->id();
            $userId = is_int($userId) ? $userId : null;
            $page->updated_by ??= $userId;
            $page->created_by ??= $userId;
        }
    }

    public function updating(Page $page): void
    {
        if ($page->isDirty('slug')) {
            SlugRedirect::query()->updateOrCreate(
                ['old_slug' => (string) $page->getOriginal('slug'), 'content_type' => 'page'],
                ['new_slug' => (string) $page->slug]
            );
        }
    }

    public function created(Page $page): void
    {
        ActivityLog::record('page.created', $page, "Halaman '{$page->title}' dibuat.");
        Cache::flush();
    }

    public function updated(Page $page): void
    {
        ActivityLog::record('page.updated', $page, "Halaman '{$page->title}' diperbarui.");
        Cache::flush();
    }

    public function deleted(Page $page): void
    {
        ActivityLog::record('page.deleted', $page, "Halaman '{$page->title}' dihapus.");
        Cache::flush();
    }
}
