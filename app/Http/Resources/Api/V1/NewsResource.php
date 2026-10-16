<?php

namespace App\Http\Resources\Api\V1;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin News */
class NewsResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        $isDetail = $request->routeIs('*.news.show') || $request->is('*/news/*');

        $base = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'featured_image' => $this->featured_image,
            'published_at' => $this->published_at?->toIso8601String(),
            'author' => $this->whenLoaded('author', fn () => $this->author?->name),
            'categories' => $this->whenLoaded('categories', fn () => $this->categories->map(fn ($c) => [
                'name' => $c->name, 'slug' => $c->slug,
            ])),
            'tags' => $this->whenLoaded('tags', fn () => $this->tags->map(fn ($t) => [
                'name' => $t->name, 'slug' => $t->slug,
            ])),
        ];

        if ($isDetail) {
            $base['content'] = $this->content;
            $base['gallery'] = $this->gallery ?? [];
            $base['seo'] = [
                'title' => $this->seo_title ?: $this->title,
                'description' => $this->seo_description,
                'canonical_url' => $this->canonical_url,
            ];
        }

        return $base;
    }
}
