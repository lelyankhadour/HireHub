<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FreelancerResource extends JsonResource
{ /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
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
            'projects_count' => $this->projects_count,
        ];
    }
}
