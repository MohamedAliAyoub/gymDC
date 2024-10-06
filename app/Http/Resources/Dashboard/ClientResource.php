<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request): array
    {
        $userDetails = $this->userDetails()->latest()->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image_url,
            'mobile' => $this->mobile,
            'created_at' => $this->created_at->format('Y-m-d'),
            'forms_updated_at' => $this->activeSubscription?->updated_at->format('Y-m-d') ?? $this->activeSubscription?->created_at->format('Y-m-d'),
            'packages' => $userDetails?->package_value,
            'form_status' => $this->form_status_value,
            'subscription' => $userDetails?->subscription_value,
        ];
    }
}
