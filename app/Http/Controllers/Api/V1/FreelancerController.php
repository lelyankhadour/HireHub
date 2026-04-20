<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\FreelancerResource;
use App\Http\Resources\FreelancerProfileResource;
use App\Services\FreelancerService;

class FreelancerController extends Controller
{
    protected FreelancerService $service;

    public function __construct(FreelancerService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $skill  = $request->query('skill');
        $cityId = $request->query('city_id');
        $sort   = $request->query('sort', 'rating');

        $freelancers = $this->service->listFreelancers($skill, $cityId, $sort);

        return FreelancerResource::collection($freelancers);
    }

    public function show(User $user)
    {
        $freelancer = $this->service->showFreelancer($user);

        return new FreelancerProfileResource($freelancer);
    }
}
