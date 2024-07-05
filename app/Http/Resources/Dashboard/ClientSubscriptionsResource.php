<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientSubscriptionsResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'duration' => $this->duration,
            'current_duration' => 6,
            'status' => $this->status_name,
            'package' => $this->packages_name,
            'started_at' => $this->started_at,
            'created_at' => $this->created_at,
        ];
    }
}
