<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Page */
class PageResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        $normalized = $this->normalizedSections();

        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'template' => $this->template,
            'featured_image' => $this->featured_image,
            'sections' => $normalized['sections'],
            'sections_version' => $normalized['version'],
            'published_at' => $this->published_at?->toIso8601String(),
            'seo' => [
                'title' => $this->seoTitle(),
                'description' => $this->seo_description,
                'canonical_url' => $this->canonical_url,
            ],
        ];
    }
}
