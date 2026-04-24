<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;
class ProjectController extends Controller
{use ResponseTrait;
    protected ProjectService $service;

    public function __construct(ProjectService $service)
    {
        $this->service = $service;
    }
    

    public function index(Request $request)
    {try{
        $projects = $this->service->list($request);
        // return ProjectResource::collection($projects);
        return $this->successResponse(ProjectResource::collection($projects), "Operation completed successfully", 200);
        }catch (\Throwable $e) {
            return $this->errorResponse("Something went wronge " . $e->getMessage(), 500);
        }
    }

    public function show(Project $project)
    {try{
        $project = $this->service->show($project);
        // return new ProjectResource($project);
          return  $this->successResponse(new ProjectResource($project), "Operation completed successfully", 200);
           }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " . $e->getMessage(), 500);
        }
    }

    public function store(StoreProjectRequest $request)
    {try{
        $project = $this->service->store($request->validated(), $request);
        // return new ProjectResource($project);
           return $this->successResponse(new ProjectResource($project), "Operation completed successfully", 201);
           }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " . $e->getMessage(), 500);
        }
    }
}
