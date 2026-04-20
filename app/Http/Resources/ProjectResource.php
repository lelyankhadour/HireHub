<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'               => $this->id,
            'title'            => $this->title,
            'description'      => $this->description,
            'budget_type'      => $this->budget_type,
            'budget_amount'    => $this->budget_amount,
            'formatted_budget' => $this->formatted_budget,
            'deadline'         => $this->deadline,
            'deadline_status'  => $this->deadline_status,
            'status'           => $this->status,

            'client' => [
                'id'         => $this->client->id,
                'full_name'  => $this->client->full_name,
                'avatar_url' => $this->client->avatar_url,
                'city'       => $this->client->city->name ?? null,
            ],
    // 'client' => new UserResource($this->whenLoaded('client')),
    // 'client' => [  'id'         => $this->client->id,],
            'tags'        => TagResource::collection($this->whenLoaded('tags')),
            'attachments' => AttachmentResource::collection($this->whenLoaded('attachments')),

            'bids_count'        => $this->bids_count,
            'reviews_avg_rating'=> $this->reviews_avg_rating,

            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
