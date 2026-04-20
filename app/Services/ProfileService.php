<?php

namespace App\Services;

use App\Models\User;

class ProfileService
{
    public function updateProfile(User $user, array $data)
    {
        $user->update([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'phone'      => $data['phone'] ?? null,
            'city_id'    => $data['city_id'] ?? null,
            'bio'        => $data['bio'] ?? null,
        ]);

        if (isset($data['skills'])) {
            $user->skills()->sync($data['skills']);
        }

        return $user->load([
            'city',
            'skills',
            'reviews.reviewer',
        ]);
    }

    public function updateProfileSkill(User $user, array $skills)
    {
        $skills = collect($skills);

        $syncData = $skills->mapWithKeys(function ($skill) {
            return [
                $skill['id'] => [
                    'years_of_experience' => $skill['years_of_experience']
                ]
            ];
        })->toArray();

        $user->skills()->sync($syncData);

        return $user->load('skills')->skills;
    }
}
