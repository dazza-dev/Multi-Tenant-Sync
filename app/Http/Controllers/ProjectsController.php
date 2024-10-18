<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProjectRequest;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectsController extends Controller
{
    public function index(Request $request): LengthAwarePaginator
    {
        $query = Project::query();

        if ($request->has('search') && $request->search) {
            $query->where('name', 'LIKE', '%'.$request->search.'%');
        }

        return $query->orderBy(
            $request->sortColumn ?? 'name',
            $request->sortDirection ?? 'ASC'
        )
            ->paginate($request->per_page);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json([
            'project' => $project,
        ], 200);
    }

    public function store(CreateProjectRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        return response()->json([
            'project' => $project,
            'message' => 'Project Created Successfully',
        ], 200);
    }

    public function update(CreateProjectRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());

        return response()->json([
            'project' => $project,
            'message' => 'Project Updated Successfully',
        ], 200);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json([
            'message' => 'Project Deleted Successfully',
        ], 200);
    }
}
