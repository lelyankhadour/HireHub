<?php

namespace App\Services;

use App\Models\Review;
use App\Models\Project;
use App\Models\User;
use App\Jobs\RecalculateFreelancerRatingJob;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function reviewProject(Project $project, array $data): Review
    {  
        return DB::transaction(function () use ($project, $data) {

            if ($project->status !== 'closed') {
                throw new \Exception("You can only review a closed project");
            }
            if ($project->client_id !== auth()->id()) {
                throw new \Exception("You are not allowed to review this project");
            }

            return $project->reviews()->create([
                'user_id' => auth()->id(),
                'rating'  => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]);
        });
    }

    public function reviewFreelancer(User $freelancer, array $data): Review
    {
        return DB::transaction(function () use ($freelancer, $data) {

        
            $hasCompletedProject = Project::closed()
            // تم استبدالها بمدل وير
                // ->ownedBy(auth()->id())
                ->completedByFreelancer($freelancer->id)
                ->exists();

            if (! $hasCompletedProject) {
                throw new \Exception("You can only review freelancers who completed a project for you");
            }

            $review = $freelancer->reviews()->create([
                'user_id' => auth()->id(),
                'rating'  => $data['rating'],
                'comment' => $data['comment'] ?? null,
            ]);

            RecalculateFreelancerRatingJob::dispatch($freelancer)->afterCommit();

            return $review;
        });
    }
}
