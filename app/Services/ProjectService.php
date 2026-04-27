<?php

namespace App\Services;

use App\Http\Resources\ProjectResource;
use App\Jobs\SendProjectPublishedEmail;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

use Illuminate\Http\Request;

class ProjectService
{
    public function list(Request $request)
    {
        $tag       = $request->query('tag');
        $minBudget = $request->query('min_budget');
        $maxBudget = $request->query('max_budget');
        $sort      = $request->query('sort', 'newest');
      $page      = $request->query('page', 1); 

         // dynamic cachekey, i have filter and sort and more i need differnt key
                $cacheKey = "projects:list:{$tag}:{$minBudget}:{$maxBudget}:{$sort}:page:{$page}";
 
    return Cache::tags(['projects'])->remember( $cacheKey ,3600,  function () use ($request) {
    $projects = Project::query()
        ->forProjectListing()
        ->filterByTag($request->tag)
        ->filterByBudgetRange($request->min_budget, $request->max_budget)
        ->when($request->sort === 'top_rated', fn($q) => $q->orderByDesc('reviews_avg_rating'))
        ->when($request->sort === 'newest', fn($q) => $q->sortByNewest())
        ->paginate(10);

    return ProjectResource::collection($projects)->response()->getData(true);
});

    }

public function store(array $data, $request)
    {
        $project = Project::create([
            'client_id'     => auth()->id(),
            'title'         => $data['title'],
            'description'   => $data['description'],
            'budget_type'   => $data['budget_type'],
            'budget_amount' => $data['budget_amount'],
            'deadline'      => $data['deadline'],
            'status'        => 'open',
        ]);

        // Tags
      // sync() is used because it correctly manages many-to-many relations.
        if (!empty($data['tags'])) {
            $project->tags()->sync($data['tags']);
        }

    
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                $project->attachments()->create([
                    'path' => $path,
                ]);
            }
        }

    // Invalidate cache after creating a new project
    // Cache::forget('projects:list');
       Cache::tags(['projects'])->flush();
SendProjectPublishedEmail::dispatch($project);

        return $project;
    }

    public function show(Project $project)
    {
        return $project->loadMissing([
            'client',
            'tags',
            'attachments',
            'bids.freelancer',
        ]);
    }
    public function closeProject(Project $project)
{

// dd($project);
    $hasAcceptedFreelancer = $project->bids()
        ->where('status', 'accepted')
        ->exists();

    if (! $hasAcceptedFreelancer) {
        throw new \Exception("You cannot close a project without an accepted freelancer");
    }

    $project->update([
        'status' => 'closed'
    ]);
 // Invalidate cache when project status changes
    // Cache::forget('projects:list');
    Cache::tags(['projects'])->flush();
    return $project;
}

}
