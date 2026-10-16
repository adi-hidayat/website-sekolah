<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsWebController extends Controller
{
    public function index(Request $request): Response
    {
        $query = News::published()->with(['categories', 'author'])->latest('published_at');

        $search = $request->string('q')->toString();
        if ($search !== '') {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('content', 'like', "%{$search}%"));
        }

        $category = $request->string('category')->toString();
        if ($category !== '') {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $category));
        }

        return Inertia::render('NewsIndex', [
            'news' => $query->paginate(9)->withQueryString(),
            'categories' => NewsCategory::orderBy('name')->get(['id', 'name', 'slug']),
            'filters' => ['q' => $search, 'category' => $category],
        ]);
    }

    public function show(string $slug): Response
    {
        $news = News::published()->with(['categories', 'tags', 'author'])->where('slug', $slug)->firstOrFail();
        $news->increment('views_count');

        return Inertia::render('NewsDetail', [
            'article' => $news,
            'related' => $news->related(),
            'seo' => [
                'title' => $news->seo_title ?: $news->title,
                'description' => $news->seo_description ?: $news->excerpt,
                'image' => $news->featured_image,
                'canonical' => $news->canonical_url,
            ],
        ]);
    }
}
