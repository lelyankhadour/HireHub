<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BidResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'amount'      => $this->amount,
            'cover_letter'=> $this->cover_letter,
// lazy laod ?!
            'freelancer' => [
                'id'         => $this->freelancer->id,
                'full_name'  => $this->freelancer->full_name,
                'avatar_url' => $this->freelancer->avatar_url,
            ],
//  'freelancer' => new FreelancerResource($this->whenLoaded('freelancer')),
    // 'freelancer' => [  'id'         => $this->freelancer->id,],

            'project_id' => $this->project_id,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
