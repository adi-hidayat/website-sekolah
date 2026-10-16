<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentWebController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('DepartmentIndex', [
            'departments' => Department::published()->orderBy('sort_order')->get(),
        ]);
    }

    public function show(string $slug): Response
    {
        $department = Department::published()->with(['teachers', 'achievements'])->where('slug', $slug)->firstOrFail();

        return Inertia::render('DepartmentDetail', [
            'department' => $department,
            'related' => Department::published()
                ->whereKeyNot($department->id)
                ->orderBy('sort_order')
                ->limit(3)
                ->get(),
            'seo' => [
                'title' => $department->seo_title ?: $department->name,
                'description' => $department->seo_description ?: $department->short_description,
                'image' => $department->banner ?: $department->thumbnail,
            ],
        ]);
    }
}
