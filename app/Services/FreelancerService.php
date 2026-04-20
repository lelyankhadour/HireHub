<?php

namespace App\Services;

use App\Models\User;

class FreelancerService
{
    public function listFreelancers($skill, $cityId, $sort)
    { 
           // Scopes encapsulate filtering logic inside the model for reusability.
        return User::query()
            ->where('role', 'freelancer')
            ->forFreelancerListing($skill, $cityId)
            ->when($sort === 'rating', fn($q) => $q->sortByRating())
            ->paginate(10);
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
