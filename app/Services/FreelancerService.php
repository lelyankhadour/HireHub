<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FreelancerService
{
    public function listFreelancers($skill, $cityId, $sort)
    {
        // Scopes encapsulate filtering logic inside the model for reusability.
        // after Cache
        // Dynamic cache key ensures each filter/sort/page combination is cached separately.
// This prevents serving incorrect cached data for different queries
// Cache Tags allow invalidating all project-related cache entries at once
// when a project is created or closed
   $cacheKey = "freelancers:list:{$skill}:{$cityId}:{$sort}";
        return Cache::tags(['freelancers'])->remember( $cacheKey, 600, function () use ($skill, $cityId, $sort) {
            return User::query()
                ->where('role', 'freelancer')
                ->forFreelancerListing($skill, $cityId)
                // ->when($sort === 'rating', fn($q) => $q->sortByRating())
                ->paginate(10)
                ->toArray();
        });
    }

    public function showFreelancer(User $user)
    {
        abort_if($user->role !== 'freelancer', 404);

        $user->load([
            'skills',
            'city',
            'freelancerProfile',
            'reviews.reviewer',
        ]);


        return $user;
    }
}
