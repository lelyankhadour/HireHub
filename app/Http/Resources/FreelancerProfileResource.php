<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FreelancerProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'full_name'      => $this->full_name,
            'avatar_url'     => $this->avatar_url,
            'city'           => $this->city->name ?? null,
            'skills'         => $this->skills->pluck('name'),
            'rating'         => $this->reviews_avg_rating,
            'rating_text'    => $this->rating_text,
            'reviews_count'  => $this->reviews_count,
            'projects_count' => $this->projects_count,

            'profile' => [
                'bio'         => $this->freelancerProfile->bio ?? null,
                'hourly_rate' => $this->freelancerProfile->hourly_rate ?? null,
                'experience'  => $this->freelancerProfile->experience ?? null,
            ],

            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
        ];
    }
}
