<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Announcement */
class AnnouncementResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        $isDetail = $request->is('*/announcements/*');

        $base = [
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'starts_at' => $this->starts_at?->toDateString(),
            'ends_at' => $this->ends_at?->toDateString(),
            'is_pinned' => (bool) $this->is_pinned,
        ];

        if ($isDetail) {
            $base['content'] = $this->content;
            $base['attachment'] = $this->attachment;
        } else {
            $base['excerpt'] = str($this->content)->stripTags()->limit(160)->toString();
        }

        return $base;
    }
}
