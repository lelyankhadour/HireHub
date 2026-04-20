<?php

namespace App\Services;

use App\Contracts\BidServiceInterface;
use App\Models\Bid;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class BidService implements BidServiceInterface
{
    public function create(array $data, int $projectId): Bid
    {
        $project = Project::findOrFail($projectId);

        return $project->bids()->create([
            'freelancer_id' => auth()->id(),
            'amount'        => $data['amount'],
            'delivery_days' => $data['delivery_days'],
            'cover_letter'  => $data['cover_letter'],
        ]);
    }

    public function accept(Bid $bid): Bid
    {
        return DB::transaction(function () use ($bid) {
            // 1) قبول العرض
            $bid->update(['status' => 'accepted']);

            // 2) تغيير حالة المشروع
            $bid->project->update(['status' => 'in_progress']);

            // 3) رفض باقي العروض
            $bid->project->bids()
                ->where('id', '!=', $bid->id)
                ->update(['status' => 'rejected']);

            return $bid->fresh(['project', 'freelancer']);
        });
    }
}
