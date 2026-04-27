<?php

namespace App\Services;

use App\Contracts\BidServiceInterface;
use App\Contracts\NotificationInterface;
use App\Enums\BidStatus;
use App\Enums\ProjectStatus;
use App\Jobs\RejectOtherBidsJob;
use App\Jobs\SendBidAcceptedEmailJob;
use App\Models\Bid;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class BidService implements BidServiceInterface
{
    public function __construct(
        private NotificationInterface $notifier
    ) {
    }

    public function create(array $data, int $projectId): Bid
    {
        $project = Project::open()->findOrFail($projectId);

        return $project->bids()->create([
            'freelancer_id' => auth()->id(),
            'amount'        => $data['amount'],
            'delivery_days' => $data['delivery_days'],
            'cover_letter'  => $data['cover_letter'],
        ]);
    }

    public function accept(Bid $bid): Bid
    {
        // Only the project owner can accept the bid
        if ($bid->project->client_id !== auth()->id()) {
            throw new \Exception("You are not allowed to accept this bid");
        }

        // Database transaction ensures all updates happen together
        return DB::transaction(function () use ($bid) {

            // Update accepted bid status using Enum
            $bid->update(['status' => BidStatus::Accepted]);

            // Update project status using Enum
            $bid->project->update(['status' => ProjectStatus::InProgress]);

            SendBidAcceptedEmailJob::dispatch($bid)->afterCommit();

  
            RejectOtherBidsJob::dispatch($bid)->afterCommit();

            // Also send notification through notifier interface
            $this->notifier->send(
                $bid->freelancer,
                "Your bid on project '{$bid->project->title}' has been accepted!"
            );

            // Return updated relations
            return $bid->fresh(['project', 'freelancer']);
        });
    }
}
