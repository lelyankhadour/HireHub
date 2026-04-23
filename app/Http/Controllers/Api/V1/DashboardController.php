<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Bid;
use App\Traits\ResponseTrait;
class DashboardController extends Controller
{use ResponseTrait;
    
    public function index()
    {try{
        // return response()->json([
        //     'total_projects'    => Project::count(),
        //     'open_projects'     => Project::where('status', 'open')->count(),
        //     'total_freelancers' => User::where('role', 'freelancer')->count(),
        //     'total_clients'     => User::where('role', 'client')->count(),
        //     'total_bids'        => Bid::count(),
        // ]);

        $data=[
                 'total_projects'    => Project::count(),
            'open_projects'     => Project::where('status', 'open')->count(),
            'total_freelancers' => User::where('role', 'freelancer')->count(),
            'total_clients'     => User::where('role', 'client')->count(),
            'total_bids'        => Bid::count(),
        ];
         return $this->successResponse($data, "Operation completed successfully", 200);

     }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " , 500);
        } 
}
}
