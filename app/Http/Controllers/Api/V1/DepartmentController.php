<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DepartmentController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $departments = Department::published()->orderBy('sort_order')->paginate(12);

        return DepartmentResource::collection($departments);
    }

    public function show(string $slug): DepartmentResource
    {
        $department = Department::published()->with('teachers')->where('slug', $slug)->firstOrFail();

        return new DepartmentResource($department);
    }
}
