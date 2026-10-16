<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PageResource;
use App\Models\Page;
use App\Models\SlugRedirect;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    public function show(string $slug): PageResource|JsonResponse
    {
        // Lookup langsung berindeks; tanpa cache model agar aman di semua driver.
        $page = Page::published()->where('slug', $slug)->first();

        if (! $page) {
            $redirect = SlugRedirect::query()->where('old_slug', $slug)->where('content_type', 'page')->first();
            if ($redirect) {
                return response()->json(['data' => null, 'redirect_to' => '/'.$redirect->new_slug], 301);
            }

            return response()->json(['message' => 'Halaman tidak ditemukan.'], 404);
        }

        return new PageResource($page);
    }
}
