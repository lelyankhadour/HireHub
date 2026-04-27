<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Bid;
use App\Traits\ResponseTrait;
use App\Enums\UserRole;
use App\Enums\ProjectStatus;

class DashboardController extends Controller
{
    use ResponseTrait;
    
    public function index()
    {
        try {
            $data = [
                'total_projects'    => Project::count(),
                'open_projects'     => Project::where('status', ProjectStatus::Open)->count(),
                'total_freelancers' => User::where('role', UserRole::Freelancer)->count(),
                'total_clients'     => User::where('role', UserRole::Client)->count(),
                'total_bids'        => Bid::count(),
            ];

            return $this->successResponse($data, "Operation completed successfully", 200);

        } catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong", 500);
        }
    }
}
