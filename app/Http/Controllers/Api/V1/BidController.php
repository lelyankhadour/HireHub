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
        // اضافة شر
        // ط للتاكد ان من قبل الغرض هو صاحب المشروع 
        $bid = Bid::with(['project', 'freelancer'])->findOrFail($id);

        $accepted = $this->bidService->accept($bid);

        // return new BidResource($accepted);
            return $this->successResponse(new BidResource($accepted), "Operation completed successfully", 200);
        
     }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " , 500);
        } 
    }
}
