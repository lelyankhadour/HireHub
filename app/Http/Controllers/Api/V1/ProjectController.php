<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected ProjectService $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $projects = $this->service->list($request);
        return ProjectResource::collection($projects);
    }

    public function show(Project $project)
    {
        $project = $this->service->show($project);
        return new ProjectResource($project);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->service->store($request->validated(), $request);
        return new ProjectResource($project);
    }
}
