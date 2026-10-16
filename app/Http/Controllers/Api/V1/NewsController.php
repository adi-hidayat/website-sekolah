<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NewsResource;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'tag' => ['nullable', 'string', 'max:100'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ]);

        $query = News::published()->with(['categories', 'tags', 'author'])->latest('published_at');

        if ($search = $request->string('search')->toString()) {
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")->orWhere('content', 'like', "%{$search}%"));
        }
        if ($category = $request->string('category')->toString()) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $category));
        }
        if ($tag = $request->string('tag')->toString()) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tag));
        }

        return NewsResource::collection($query->paginate($request->integer('per_page', 9)));
    }

    public function show(string $slug): NewsResource
    {
        $news = News::published()->with(['categories', 'tags', 'author'])->where('slug', $slug)->firstOrFail();
        $news->increment('views_count');

        return (new NewsResource($news))->additional([
            'meta' => ['related' => $news->related()->map(fn ($r) => [
                'title' => $r->title, 'slug' => $r->slug,
                'featured_image' => $r->featured_image, 'published_at' => $r->published_at?->toIso8601String(),
            ])->values()],
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = Cache::remember('news-categories-array', 3600, fn () => NewsCategory::orderBy('name')->get(['name', 'slug'])->toArray());

        return response()->json(['data' => $categories]);
    }
}
