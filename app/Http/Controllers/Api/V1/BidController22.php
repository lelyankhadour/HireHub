<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBidRequest;
use App\Models\Bid;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\BidStoreRequest;
use App\Http\Resources\BidResource;

class BidController extends Controller
{
//    single responsiblilty principle

public function store(StoreBidRequest $request, $projectId)
{
    // problem we have here 12 querey !!!!
    $project = Project::findOrFail($projectId);

    $data = $request->validated();

    $bid = $project->bids()->create([
        'freelancer_id' => auth()->id(),
        'amount'        => $data['amount'],
        'delivery_days' => $data['delivery_days'],
        'cover_letter'  => $data['cover_letter'],
    ]);

    return new BidResource($bid->load('project', 'freelancer'));
    // try to solve problem  faild
    // +4 query 
//     return new BidResource(
//     $bid->load([
//         'project.client',
//         'project.tags',
//         'freelancer.city.country',
//         'freelancer.skills',
//     ])
// );

}

public function show($id)
{
    $bid = Bid::with(['freelancer', 'project'])->findOrFail($id);

    return new BidResource($bid);
}
   
}
