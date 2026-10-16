<?php

namespace App\Http\Resources\Api\V1;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Department */
class DepartmentResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        $base = [
            'name' => $this->name,
            'code' => $this->code,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'thumbnail' => $this->thumbnail,
            'sort_order' => $this->sort_order,
            'seo' => [
                'title' => $this->seo_title ?: $this->name,
                'description' => $this->seo_description,
            ],
        ];

        if ($request->routeIs('*.departments.show') || $request->is('*/departments/*')) {
            $base['description'] = $this->description;
            $base['banner'] = $this->banner;
            $base['competencies'] = $this->competencies ?? [];
            $base['teachers'] = $this->whenLoaded('teachers', fn () => $this->teachers->map(fn ($t) => [
                'name' => $t->name,
                'position' => $t->position,
                'subject' => $t->subject,
                'photo' => $t->photo,
            ]));
        }

        return $base;
    }
}
