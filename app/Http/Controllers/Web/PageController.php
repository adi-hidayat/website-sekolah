<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function show(string $slug): Response
    {
        $page = Page::published()->where('slug', $slug)->firstOrFail();

        return Inertia::render('PageDetail', [
            'page' => [
                'title' => $page->title,
                'slug' => $page->slug,
                'excerpt' => $page->excerpt,
                'content' => $page->content,
                'template' => $page->template,
                'featured_image' => $page->featured_image,
                'sections' => $page->normalizedSections()['sections'],
                'breadcrumbs' => [
                    ['label' => 'Beranda', 'href' => '/'],
                    ['label' => $page->title],
                ],
                'seo' => [
                    'title' => $page->seoTitle(),
                    'description' => $page->seo_description,
                    'image' => $page->featured_image,
                    'canonical' => $page->canonical_url,
                ],
            ],
        ]);
    }
}
