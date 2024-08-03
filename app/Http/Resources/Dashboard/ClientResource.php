<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'forms_updated_at' => $this->activeSubscription?->updated_at ?? $this->activeSubscription?->ctreated_at,
            'packages' => $this->userDetails()->latest()->first()?->packages,
            'form_status' => $this->userDetails()->latest()->first()?->form_status,
            'created_at' => $this->created_at,
        ];
    }
}
