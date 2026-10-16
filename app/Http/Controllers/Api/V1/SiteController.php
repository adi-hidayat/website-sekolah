<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Support\MenuResolver;
use App\Support\SitePresentation;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SiteController extends Controller
{
    public function settings(): JsonResponse
    {
        $general = SitePresentation::general();

        $data = [
            'school_name' => $general['school_name'] ?? null,
            'short_name' => $general['short_name'] ?? null,
            'address' => $general['address'] ?? null,
            'phone' => $general['phone'] ?? null,
            'email' => $general['email'] ?? null,
            'website' => $general['website'] ?? null,
            'logo' => $general['logo'] ?? null,
            'principal_name' => $general['principal_name'] ?? null,
            'principal_welcome' => $general['principal_welcome'] ?? null,
            'principal_photo' => $general['principal_photo'] ?? null,
            'vision' => $general['vision'] ?? null,
            'mission' => $general['mission'] ?? null,
            'social_links' => $general['social_links'] ?? [],
            'statistics' => $general['statistics'] ?? [],
            'theme' => SitePresentation::theme(),
        ];

        return response()->json(['data' => $data]);
    }

    public function menu(string $location): JsonResponse
    {
        $tree = Cache::remember("menu-{$location}", 3600, function () use ($location) {
            $menu = Menu::query()->where('location', $location)->first();
            if (! $menu) {
                return [];
            }

            $items = $menu->items()->with(['page', 'department'])->get();

            return MenuResolver::tree($items);
        });

        return response()->json(['data' => $tree]);
    }
}
