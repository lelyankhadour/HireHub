<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'full_name'  => $this->full_name,
            'avatar_url' => $this->avatar_url,

            'city' => $this->whenLoaded('city', fn () => [
                'id'   => $this->city->id,
                'name' => $this->city->name,
                'country' => $this->whenLoaded('city.country', fn () => [
                    'id'   => $this->city->country->id,
                    'name' => $this->city->country->name,
                ]),
            ]),
        ];
    }
}
