<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectService
{
    public function list(Request $request)
    {
        $tag       = $request->query('tag');
        $minBudget = $request->query('min_budget');
        $maxBudget = $request->query('max_budget');
        $sort      = $request->query('sort', 'newest');
   
     // Query scopes are used to keep filtering logic reusable and readable
        return Project::query()
            ->forProjectListing()
            ->filterByTag($tag)
            ->filterByBudgetRange($minBudget, $maxBudget)
    
            // Conditional sorting is handled through "when" for fluent readability.
            ->when($sort === 'top_rated', fn($q) => $q->orderByDesc('reviews_avg_rating'))

            ->when($sort === 'newest', fn($q) => $q->sortByNewest())
            ->paginate(10);
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

        // Attachments
        // not testing 
        // crud !!
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                $project->attachments()->create([
                    'path' => $path,
                ]);
            }
        }

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
}
