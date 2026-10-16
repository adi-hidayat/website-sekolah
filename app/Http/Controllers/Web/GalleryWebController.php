<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Inertia\Inertia;
use Inertia\Response;

class GalleryWebController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('GalleryIndex', [
            'galleries' => Gallery::published()
                ->withCount('items')
                ->latest()
                ->paginate(9),
        ]);
    }

    public function show(string $slug): Response
    {
        $gallery = Gallery::published()->with('items')->where('slug', $slug)->firstOrFail();

        return Inertia::render('GalleryDetail', [
            'gallery' => $gallery,
            'related' => Gallery::published()
                ->whereKeyNot($gallery->id)
                ->latest()
                ->limit(3)
                ->get(['id', 'title', 'slug', 'cover_image']),
            'seo' => [
                'title' => $gallery->title,
                'description' => $gallery->description,
                'image' => $gallery->cover_image,
            ],
        ]);
    }
}
