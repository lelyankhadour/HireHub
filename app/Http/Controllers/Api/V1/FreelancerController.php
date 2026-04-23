<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\FreelancerResource;
use App\Http\Resources\FreelancerProfileResource;
use App\Services\FreelancerService;
use App\Traits\ResponseTrait;
class FreelancerController extends Controller
{use ResponseTrait;
    protected FreelancerService $service;

    public function __construct(FreelancerService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {try{
        $skill  = $request->query('skill');
        $cityId = $request->query('city_id');
        $sort   = $request->query('sort', 'rating');

        $freelancers = $this->service->listFreelancers($skill, $cityId, $sort);

        // return FreelancerResource::collection($freelancers);
                 return $this->successResponse(FreelancerResource::collection($freelancers), "Operation completed successfully", 200);

  }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " , 500);
        }    }

    public function show(User $user)
    {try{
        $freelancer = $this->service->showFreelancer($user);

        // return new FreelancerProfileResource($freelancer);
                 return $this->successResponse( new FreelancerProfileResource($freelancer), "Operation completed successfully", 200);

      }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " , 500);
        }}
}
