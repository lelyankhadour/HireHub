<?php 
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBidRequest;
use App\Http\Resources\BidResource;
use App\Models\Bid;
use App\Traits\ResponseTrait;
class BidController extends Controller
{use ResponseTrait;
    public function __construct(
        protected \App\Contracts\BidServiceInterface $bidService
    ) {}

    public function store(StoreBidRequest $request, $projectId)
    {try{
        $bid = $this->bidService->create($request->validated(), (int) $projectId);
// cost more query
        // $bid->load([
        //     'project.client.city.country',
        //     'project.tags',
        //     'freelancer.city.country',
        //     'freelancer.skills',
        // ]);

        // return new BidResource($bid);
            // return new BidResource($bid->load('project', 'freelancer'));
                return $this->successResponse( new BidResource($bid->load('project', 'freelancer')), "Operation completed successfully", 200);
            
     }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " , 500);
        } 

    }

    public function accept($id)
    {try{
 
        $bid = Bid::with(['project', 'freelancer'])->findOrFail($id);

        $accepted = $this->bidService->accept($bid);

        // return new BidResource($accepted);
            return $this->successResponse(new BidResource($accepted), "Operation completed successfully", 200);
        
     }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " , 500);
        } 
    }
//   public function show($id)
// {
//     $bid = Bid::with(['freelancer', 'project'])->findOrFail($id);

//     return new BidResource($bid);
// }

// public function store(StoreBidRequest $request, $projectId)
// {
//     // problem we have here 12 querey !!!!
//     $project = Project::findOrFail($projectId);

//     $data = $request->validated();

//     $bid = $project->bids()->create([
//         'freelancer_id' => auth()->id(),
//         'amount'        => $data['amount'],
//         'delivery_days' => $data['delivery_days'],
//         'cover_letter'  => $data['cover_letter'],
//     ]);

//     return new BidResource($bid->load('project', 'freelancer'));
//     // try to solve problem  faild
//     // +4 query 
// //     return new BidResource(
// //     $bid->load([
// //         'project.client',
// //         'project.tags',
// //         'freelancer.city.country',
// //         'freelancer.skills',
// //     ])
// // );

// }


}
