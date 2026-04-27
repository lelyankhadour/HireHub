<?php

namespace App\Services;

use App\Enums\BudgetType;
use App\Enums\ProjectStatus;
use App\Http\Resources\ProjectResource;
use App\Jobs\SendProjectPublishedEmail;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProjectService
{
    public function list(Request $request)
    {
        $tag       = $request->query('tag');
        $minBudget = $request->query('min_budget');
        $maxBudget = $request->query('max_budget');
        $sort      = $request->query('sort', 'newest');
        $page      = $request->query('page', 1);

        // Cache key dynamic
        $cacheKey = "projects:list:{$tag}:{$minBudget}:{$maxBudget}:{$sort}:page:{$page}";

        return Cache::tags(['projects'])->remember($cacheKey, 3600, function () use ($request) {
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
            'budget_type'   => BudgetType::from($data['budget_type']),
            'budget_amount' => $data['budget_amount'],
            'deadline'      => $data['deadline'],
            'status'        => ProjectStatus::Open,
        ]);

        // Tags
        if (!empty($data['tags'])) {
            $project->tags()->sync($data['tags']);
        }

        // Attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                $project->attachments()->create([
                    'path' => $path,
                ]);
            }
        }

        // Invalidate cache
        Cache::tags(['projects'])->flush();

        // Dispatch email job
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
        $hasAcceptedFreelancer = $project->bids()
            ->where('status', 'accepted')
            ->exists();

        if (!$hasAcceptedFreelancer) {
            throw new \Exception("You cannot close a project without an accepted freelancer");
        }

        $project->update([
            'status' => ProjectStatus::Closed,
        ]);

        // Invalidate cache
        Cache::tags(['projects'])->flush();

        return $project;
    }
}
