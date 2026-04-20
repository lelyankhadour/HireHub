<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'full_name'    => $this->full_name,
            'email'        => $this->email,
            'city'         => $this->city->name ?? null,
            'phone'        => $this->phone,
            'avatar_url'   => $this->avatar_url,
            'member_since' => $this->member_since,
        ];
    }
}
