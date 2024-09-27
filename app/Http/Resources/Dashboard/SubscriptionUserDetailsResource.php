<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionUserDetailsResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'image' => $this->image_url,
            'age' => $this->getLatestUserDetails->age ?? null,
            'weight' => $this->getLatestUserDetails->weight ?? null,
            'height' => $this->getLatestUserDetails->height ?? null,
            'in_body_image' => $this->getLatestUserDetails->in_body_url ?? 'No in body image found',
            'created_at' => $this->getLatestUserDetails->created_at->format('Y-m-d'),
        ];
    }
}
