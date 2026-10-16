<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $homepage = Page::published()->where('slug', 'beranda')->first();

        return Inertia::render('Home', [
            'homepage' => $homepage ? [
                'title' => $homepage->title,
                'sections' => $homepage->normalizedSections()['sections'],
                'seo' => [
                    'title' => $homepage->seoTitle(),
                    'description' => $homepage->seo_description,
                    'image' => $homepage->featured_image,
                ],
            ] : null,
        ]);
    }
}
