<?php 
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBidRequest;
use App\Http\Resources\BidResource;
use App\Models\Bid;

class BidController extends Controller
{
    public function __construct(
        protected \App\Contracts\BidServiceInterface $bidService
    ) {}

    public function store(StoreBidRequest $request, $projectId)
    {
        $bid = $this->bidService->create($request->validated(), (int) $projectId);
// cost more query
        // $bid->load([
        //     'project.client.city.country',
        //     'project.tags',
        //     'freelancer.city.country',
        //     'freelancer.skills',
        // ]);

        // return new BidResource($bid);
            return new BidResource($bid->load('project', 'freelancer'));

    }

    public function accept($id)
    {
        $bid = Bid::with(['project', 'freelancer'])->findOrFail($id);

        $accepted = $this->bidService->accept($bid);

        return new BidResource($accepted);
    }
}
