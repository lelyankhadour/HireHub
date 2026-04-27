<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Project;
use App\Models\User;
use App\Services\ReviewService;
use App\Traits\ResponseTrait;

class ReviewController extends Controller
{
    use ResponseTrait;

    public function __construct(private ReviewService $reviewService) {}

    public function reviewProject($project, StoreReviewRequest $request)
    {
        try {
   
            $project = Project::findOrFail($project);

            $review = $this->reviewService->reviewProject($project, $request->validated());

            return $this->successResponse($review, 'Project reviewed successfully',201);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    public function reviewFreelancer($user, StoreReviewRequest $request)
    {
        try {
          
            $freelancer = User::findOrFail($user);

            $review = $this->reviewService->reviewFreelancer($freelancer, $request->validated());

            return $this->successResponse($review, 'Freelancer reviewed successfully');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
