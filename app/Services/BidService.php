<?php

namespace App\Services;

use App\Contracts\BidServiceInterface;
use App\Jobs\RejectOtherBidsJob;
use App\Jobs\SendBidAcceptedEmailJob;
use App\Models\Bid;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class BidService implements BidServiceInterface
{
    public function create(array $data, int $projectId): Bid
    {
        $project = Project::open()->findOrFail($projectId);

        return $project->bids()->create([
            'freelancer_id' => auth()->id(),
            'amount' => $data['amount'],
            'delivery_days' => $data['delivery_days'],
            'cover_letter' => $data['cover_letter'],
        ]);
    }

    public function accept(Bid $bid): Bid
    {
        //only owner the project can accept the bid
        if ($bid->project->client_id !== auth()->id()) {
            throw new \Exception("You are not allowed to accept this bid");
        }
        // I used a database transaction here to make sure all updates happen together
        return DB::transaction(function () use ($bid) {

            $bid->update(['status' => 'accepted']);


            $bid->project->update(['status' => 'in_progress']);

            SendBidAcceptedEmailJob::dispatch($bid)->afterCommit();

            RejectOtherBidsJob::dispatch($bid)->afterCommit();
            // we don't need this code any more the job do it in the background

            // $bid->project->bids()
            //     ->where('id', '!=', $bid->id)
            //     ->update(['status' => 'rejected']);
            // // fresh() is used to return updated relations without reloading everything manually.
            return $bid->fresh(['project', 'freelancer']);
        });
    }
}
