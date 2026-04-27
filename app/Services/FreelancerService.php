<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FreelancerService
{
    public function listFreelancers($skill, $cityId, $sort)
    {
        // Scopes encapsulate filtering logic inside the model for reusability.
        // Dynamic cache key ensures each filter/sort/page combination is cached separately.
        // This prevents serving incorrect cached data for different queries.
        // Cache Tags allow invalidating all freelancer-related cache entries at once
        // when a freelancer updates availability or skills.

        $cacheKey = "freelancers:list:{$skill}:{$cityId}:{$sort}";

        return Cache::tags(['freelancers'])->remember($cacheKey, 600, function () use ($skill, $cityId, $sort) {
            return User::query()
                // ->where('role', 'freelancer')
                // Using Enum for cleaner and safer role comparison
                ->where('role', UserRole::Freelancer)

                ->forFreelancerListing($skill, $cityId)

                // Conditional sorting is handled through "when" for fluent readability.
                ->when($sort === 'rating', fn($q) => $q->sortByRating())

                ->paginate(10)
                ->toArray();
        });
    }

    public function showFreelancer(User $user)
    {
        // abort_if($user->role !== 'freelancer', 404);
        // Using Enum for safer validation
        abort_if($user->role !== UserRole::Freelancer, 404);

        // Relations are loaded here to return a complete freelancer profile response
        // without overloading the controller.
        $user->load([
            'skills',
            'city',
            'freelancerProfile',
            'reviews.reviewer',
        ]);

        return $user;
    }
}
