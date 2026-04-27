<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ProfileService
{
    public function updateProfile(User $user, array $data)
    {
           $oldStatus = $user->availability_status;
        $user->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'phone'      => $data['phone'] ?? null,
            'city_id'    => $data['city_id'] ?? null,
            'bio'        => $data['bio'] ?? null,
            'availability_status'        => $data['availability_status'] ?? 'available',
           
        ]);

        if (isset($data['skills'])) {
            $user->skills()->sync($data['skills']);
        }
          if ($oldStatus !== $user->availability_status) {
         Cache::tags(['freelancers'])->flush();
    }
        //  Cache::forget('freelancers:list');
        //  Cache::tags(['freelancers'])->flush();

    // Relations are loaded here to return a complete profile response
        // without overloading the controller.
        return $user->load([
            'city',
            'skills',
            'reviews.reviewer',
        ]);
    }

    public function updateProfileSkill(User $user, array $skills)
    {
         // Transforming the skills array into pivot format is done here
        // because it is part of business logic, not presentation logic.
        $skills = collect($skills);

        $syncData = $skills->mapWithKeys(function ($skill) {
            return [
                $skill['id'] => [
                    'years_of_experience' => $skill['years_of_experience']
                ]
            ];
        })->toArray();

        $user->skills()->sync($syncData);
        // Cache::forget('freelancers:list');
             Cache::tags(['freelancers'])->flush();

        return $user->load('skills')->skills;
    }
}
