<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'rating'    => $this->rating,
            'comment'   => $this->comment,
            'client'    => [
                'id'         => $this->client->id ?? null,
                'full_name'  => $this->client->full_name ?? null,
                'avatar_url' => $this->client->avatar_url ?? null,
            ],
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
