<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Inertia\Inertia;
use Inertia\Response;

class AnnouncementWebController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('AnnouncementIndex', [
            'announcements' => Announcement::active()->orderByDesc('is_pinned')->latest()->paginate(10),
        ]);
    }

    public function show(string $slug): Response
    {
        $announcement = Announcement::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return Inertia::render('AnnouncementDetail', [
            'announcement' => $announcement,
            'seo' => [
                'title' => $announcement->title,
                'description' => str($announcement->content)->stripTags()->limit(160)->toString(),
            ],
        ]);
    }
}
