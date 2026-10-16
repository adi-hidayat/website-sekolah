<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AnnouncementController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $announcements = Announcement::active()
            ->orderByDesc('is_pinned')->orderBy('sort_order')->latest()
            ->paginate(10);

        return AnnouncementResource::collection($announcements);
    }

    public function show(string $slug): AnnouncementResource
    {
        $announcement = Announcement::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return new AnnouncementResource($announcement);
    }
}
