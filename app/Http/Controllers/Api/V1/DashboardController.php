<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Project;
use App\Models\Bid;

class DashboardController extends Controller
{
    
    public function index()
    {
        return response()->json([
            'total_projects'    => Project::count(),
            'open_projects'     => Project::where('status', 'open')->count(),
            'total_freelancers' => User::where('role', 'freelancer')->count(),
            'total_clients'     => User::where('role', 'client')->count(),
            'total_bids'        => Bid::count(),
        ]);
    }
}
