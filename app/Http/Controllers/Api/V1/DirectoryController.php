<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\Facility;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DirectoryController extends Controller
{
    public function facilities(): JsonResponse
    {
        $facilities = Cache::remember('facilities-array', 3600, fn () => Facility::active()->orderBy('sort_order')->get()->toArray());

        return response()->json(['data' => $facilities]);
    }

    public function achievements(Request $request): JsonResponse
    {
        $query = Achievement::published()->with('department')->latest('achieved_at');
        if ($request->filled('department')) {
            $query->whereHas('department', fn ($q) => $q->where('slug', $request->string('department')));
        }

        return response()->json(['data' => $query->paginate(12)]);
    }

    public function galleries(): JsonResponse
    {
        $galleries = Gallery::published()->with('items')->latest()->paginate(9);

        return response()->json(['data' => $galleries]);
    }
}
